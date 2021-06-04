<?php

namespace App\Http\Controllers;

use Auth;
use App\Order;
use App\OrderItem;
use App\User;
use App\Invoice;
use App\StoreInvoice;
use App\PaymentProfile;
use App\Notification;
use Illuminate\Http\Request;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;
use PDF;

class AuthorizeController extends Controller {

    /**
     * Invoices detail method.
     *
     * @return \Illuminate\Http\Response
     */
    public function order_detail($orderID) {
        $orderData = Order::with(['itemsarr'])->where('order_id', $orderID)->first();
        $orderDataArr = json_decode($orderData->items, true);
        $shipping_phone = ($orderDataArr['shipping_address']['phone']) ? $orderDataArr['shipping_address']['phone'] : $orderDataArr['customer']['phone'];
        return view('common.orderitems', ['orderData' => $orderData, 'shipping_address' => $orderDataArr['shipping_address'], 'shipping_phone' => $shipping_phone]);
    }

    /**
     * Invoices log method.
     *
     * @return \Illuminate\Http\Response
     */
    public function showinvoiceslog(Request $request) {
        $login_user = auth()->user()->username;
        if ($request->ajax()) {
            $paid_status = $request['paid_status'];
            if ($paid_status == 1) {
                $q = Invoice::where('store_domain', $login_user)->where('paid_status', '>', 0);
            } else {
                $q = Invoice::where(['store_domain' => $login_user, 'paid_status' => $paid_status]);
            }

            $TotalData = $q->count();
            $limit = $request->input('length');
            $start = $request->input('start');
            $columnindex = $request['order']['0']['column'];
            $orderby = $request['columns'][$columnindex]['data'];
            $order = $orderby != "" ? $request['order']['0']['dir'] : "";
            $draw = $request['draw'];
            $response = $q->orderBy($orderby, $order)->offset($start)->limit($limit)->get();
            if (!$response) {
                $InvoiceData = [];
                $paging = [];
            } else {
                $InvoiceData = $response;
                $paging = $response;
            }

            $Data = array();
            $i = 1;
            foreach ($InvoiceData as $Invoice) {
                $actionBtn = '';
                $u['id'] = $Invoice->id;
                $u['store_domain'] = $Invoice->store_domain;
                $u['admin_price_total'] = currency($Invoice->invoice_total, 'USD', currency()->getUserCurrency());
                $u['other_charges'] = currency($Invoice->other_charges, 'USD', currency()->getUserCurrency());
                $invoice_grand_total = ($Invoice->invoice_total + $Invoice->other_charges);
                $actionBtn .= '<a href="' . url('showinvoicedetail/' . $Invoice->id) . '" class="btn btn-warning margin2px" title="View Invoice Detail"><i class="fa fa-eye"></i> View Invoice Detail</a> <a href="' . url('downloadinvoice/' . $Invoice->id) . '" class="btn btn-success margin2px" title="View Invoice Detail"><i class="fa fa-download"></i> Download Invoice</a>';
                if ($paid_status == 0) {
                    $actionBtn .= ' <a href="javascript:void(0)" data-id="' . $Invoice->id . '" data-val="' . $invoice_grand_total . '" class="btn btn-danger pay_now_btn">Pay Now</a>';
                }
                $u['created_at'] = date('M d, Y H:i:s', strtotime($Invoice->created_at));
                $u['action'] = $actionBtn;

                $Data[] = $u;
                $i++;
                unset($u);
            }
            $return = [
                "draw" => intval($draw),
                "recordsFiltered" => intval($TotalData),
                "recordsTotal" => intval($TotalData),
                "data" => $Data
            ];
            return $return;
        } else {
            $userCardProfiles = [];
            $paymentProfiles = PaymentProfile::where(['store_domain' => auth()->user()->username])->orderBy('created_at', 'desc')->get();
            foreach ($paymentProfiles as $paymentProfile) {
                $userCardProfiles[] = $this->getCustomerPaymentProfile($paymentProfile->profile_id, $paymentProfile->payment_profile_id, $paymentProfile->id);
            }
        }
        //dd($userCardProfiles);
        return view('showinvoiceslog', ['userCardProfiles' => $userCardProfiles]);
    }

    /**
     * Invoices detail method.
     *
     * @return \Illuminate\Http\Response
     */
    public function showinvoicedetail(Request $request, $invoiceID) {
        $userCardProfiles = [];
        $paymentProfiles = PaymentProfile::where(['store_domain' => auth()->user()->username])->orderBy('created_at', 'desc')->get();
        foreach ($paymentProfiles as $paymentProfile) {
            $userCardProfiles[] = $this->getCustomerPaymentProfile($paymentProfile->profile_id, $paymentProfile->payment_profile_id, $paymentProfile->id);
        }
        $mainInvoice = Invoice::find($invoiceID);
        $invoiceIDs = json_decode($mainInvoice->store_invoice_ids, true);
        $invoice_items = Invoice::show_invoice_data(helGetSupplierID(auth()->user()->id), $invoiceIDs);
        return view('common.showinvoicedetail', ['storeData' => auth()->user(), 'invoice_items' => $invoice_items, 'mainInvoice' => $mainInvoice, 'userCardProfiles' => $userCardProfiles]);
    }

    public function downloadinvoice($invoiceID) {
        $mainInvoice = Invoice::find($invoiceID);
        $invoiceIDs = json_decode($mainInvoice->store_invoice_ids, true);
        $invoice_items = Invoice::show_invoice_data(helGetSupplierID(auth()->user()->id), $invoiceIDs);
        // return view('downloadinvoice', ['storeData' => auth()->user(), 'invoice_items' => $invoice_items, 'mainInvoice' => $mainInvoice]);
        $pdf = PDF::loadView('common.downloadinvoice', ['storeData' => auth()->user(), 'mainInvoice' => $mainInvoice, 'invoice_items' => $invoice_items]);
        $pdf->setPaper('a4')->setWarnings(false);
        //$pdf->setOptions(['defaultFont' => 'Arial']);
        return $pdf->download($invoiceID . '.pdf');
    }

    public function index() {
        return view('checkout.authorize');
    }

    public function chargeCreditCard(Request $request) {
        die("You cannot access this page!");
        /* code verify through middelware
          $uUser = User::where('username', auth()->user()->username)->first();
          $cUser = $uUser->providers->where('provider', 'shopify')->first();
          $shopify = \Shopify::retrieve($uUser->username, $cUser->provider_token);
          $optionsData = [
          'application_charge' => [
          "name" => "DSA Initiation",
          "price" => 300.0,
          'test' => false,
          "return_url" => route('shopify.one-time-subscribe', ['storeId' => auth()->user()->id])
          ]
          ];
          $reqResponse = $shopify->create('application_charges', $optionsData); */
        //dd($reqResponse['application_charge']['confirmation_url']);
        return redirect($reqResponse['application_charge']['confirmation_url']);
    }

    public function createCustomerProfile($user_cc_data) {
        /* Create a merchantAuthenticationType object with authentication details
          retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(config('services.authorize.login'));
        $merchantAuthentication->setTransactionKey(config('services.authorize.key'));

        // Set the transaction's refId
        $refId = 'ref' . time();

        // Create a Customer Profile Request
        //  1. (Optionally) create a Payment Profile
        //  2. (Optionally) create a Shipping Profile
        //  3. Create a Customer Profile (or specify an existing profile)
        //  4. Submit a CreateCustomerProfile Request
        //  5. Validate Profile ID returned
        // Set credit card information for payment profile
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($user_cc_data['cnumber']);
        if ($user_cc_data['card_expiry_month'] < 10) {
            $user_cc_data['card_expiry_month'] = "0" . $user_cc_data['card_expiry_month'];
        }
        $expiry = $user_cc_data['card_expiry_year'] . '-' . $user_cc_data['card_expiry_month'];
        $creditCard->setExpirationDate($expiry);
        $creditCard->setCardCode($user_cc_data['ccode']);
        $paymentCreditCard = new AnetAPI\PaymentType();
        $paymentCreditCard->setCreditCard($creditCard);

        // Create the Bill To info for new payment type
        $billTo = new AnetAPI\CustomerAddressType();
        $billTo->setFirstName(auth()->user()->name);
        $billTo->setLastName("");
        $billTo->setCompany(auth()->user()->username);
        $billTo->setAddress(auth()->user()->billing_address);
        $billTo->setCity(auth()->user()->city);
        $billTo->setState(auth()->user()->state);
        $billTo->setZip(auth()->user()->zip_code);
        $billTo->setCountry(auth()->user()->country);
        $billTo->setPhoneNumber(auth()->user()->phone);
        $billTo->setfaxNumber("");

        // Create a customer shipping address
        $customerShippingAddress = new AnetAPI\CustomerAddressType();
        $customerShippingAddress->setFirstName(auth()->user()->name);
        $customerShippingAddress->setLastName("");
        $customerShippingAddress->setCompany(auth()->user()->username);
        $customerShippingAddress->setAddress(auth()->user()->billing_address);
        $customerShippingAddress->setCity(auth()->user()->city);
        $customerShippingAddress->setState(auth()->user()->state);
        $customerShippingAddress->setZip(auth()->user()->zip_code);
        $customerShippingAddress->setCountry(auth()->user()->country);
        $customerShippingAddress->setPhoneNumber(auth()->user()->phone);
        $customerShippingAddress->setfaxNumber("");

        // Create an array of any shipping addresses
        $shippingProfiles[] = $customerShippingAddress;


        // Create a new CustomerPaymentProfile object
        $paymentProfile = new AnetAPI\CustomerPaymentProfileType();
        $paymentProfile->setCustomerType('individual');
        $paymentProfile->setBillTo($billTo);
        $paymentProfile->setPayment($paymentCreditCard);
        $paymentProfiles[] = $paymentProfile;


        // Create a new CustomerProfileType and add the payment profile object
        $customerProfile = new AnetAPI\CustomerProfileType();
        $customerProfile->setDescription("Order Invoices Payment");
        $customerProfile->setMerchantCustomerId("M_" . time());
        $customerProfile->setEmail(auth()->user()->email);
        $customerProfile->setpaymentProfiles($paymentProfiles);
        $customerProfile->setShipToList($shippingProfiles);


        // Assemble the complete transaction request
        $request = new AnetAPI\CreateCustomerProfileRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setProfile($customerProfile);

        // Create the controller and get the response
        $controller = new AnetController\CreateCustomerProfileController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        $result = false;
        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            //echo "Succesfully created customer profile : " . $response->getCustomerProfileId() . "\n";
            $paymentProfiles = $response->getCustomerPaymentProfileIdList();
            //echo "SUCCESS: PAYMENT PROFILE ID : " . $paymentProfiles[0] . "\n";
            $profileArr = [];
            $profileArr['store_id'] = auth()->user()->id;
            $profileArr['store_domain'] = auth()->user()->username;
            $profileArr['profile_id'] = $response->getCustomerProfileId();
            $profileArr['payment_profile_id'] = $paymentProfiles[0];
            if (PaymentProfile::create($profileArr)) {
                $result = true;
            } else {
                $result = false;
            }
        } else {
            $result = false;
            //echo "ERROR :  Invalid response\n";
            //$errorMessages = $response->getMessages()->getMessage();
            //echo "Response : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText() . "\n";
        }
        return $result;
    }

    public function getCustomerPaymentProfile($customerProfileId, $customerPaymentProfileId, $itemProfileId) {
        /* Create a merchantAuthenticationType object with authentication details
          retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(config('services.authorize.login'));
        $merchantAuthentication->setTransactionKey(config('services.authorize.key'));

        // Set the transaction's refId
        $refId = 'ref' . time();

        //request requires customerProfileId and customerPaymentProfileId
        $request = new AnetAPI\GetCustomerPaymentProfileRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setCustomerProfileId($customerProfileId);
        $request->setCustomerPaymentProfileId($customerPaymentProfileId);

        $controller = new AnetController\GetCustomerPaymentProfileController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        $profileResultArr = [];
        if (($response != null)) {
            if ($response->getMessages()->getResultCode() == "Ok") {
                $profileResultArr['item_profile_id'] = $itemProfileId;
                $profileResultArr['profile_id'] = $customerProfileId;
                $profileResultArr['payment_profile_id'] = $customerPaymentProfileId;
                $profileResultArr['card_4_digit'] = $response->getPaymentProfile()->getPayment()->getCreditCard()->getCardNumber();
                $profileResultArr['card_type'] = $response->getPaymentProfile()->getPayment()->getCreditCard()->getCardType();
                //echo "GetCustomerPaymentProfile SUCCESS: " . "\n";
                //echo "Customer Payment Profile Id: " . $response->getPaymentProfile()->getCustomerPaymentProfileId() . "\n";
                //echo "Customer Payment Profile Billing Address: " . $response->getPaymentProfile()->getbillTo()->getAddress() . "\n";
                //echo "Customer Payment Profile Card Last 4 " . $response->getPaymentProfile()->getPayment()->getCreditCard()->getCardNumber() . "\n";

                if ($response->getPaymentProfile()->getSubscriptionIds() != null) {
                    if ($response->getPaymentProfile()->getSubscriptionIds() != null) {

                        echo "List of subscriptions:";
                        foreach ($response->getPaymentProfile()->getSubscriptionIds() as $subscriptionid)
                            echo $subscriptionid . "\n";
                    }
                }
            } else {
                echo "GetCustomerPaymentProfile ERROR :  Invalid response\n";
                $errorMessages = $response->getMessages()->getMessage();
                echo "Response : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText() . "\n";
            }
        } else {
            echo "NULL Response Error";
        }
        return $profileResultArr;
    }

    public function chargeCustomerProfile($profileid, $paymentprofileid, $amount) {
        /* Create a merchantAuthenticationType object with authentication details
          retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(config('services.authorize.login'));
        $merchantAuthentication->setTransactionKey(config('services.authorize.key'));

        // Set the transaction's refId
        $refId = 'ref' . time();

        $profileToCharge = new AnetAPI\CustomerProfilePaymentType();
        $profileToCharge->setCustomerProfileId($profileid);
        $paymentProfile = new AnetAPI\PaymentProfileType();
        $paymentProfile->setPaymentProfileId($paymentprofileid);
        $profileToCharge->setPaymentProfile($paymentProfile);

        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setProfile($profileToCharge);

        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);
        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if ($response != null) {
            if ($response->getMessages()->getResultCode() == "Ok") {
                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getMessages() != null) {
                    echo " Transaction Response code : " . $tresponse->getResponseCode() . "\n";
                    echo "Charge Customer Profile APPROVED  :" . "\n";
                    echo " Charge Customer Profile AUTH CODE : " . $tresponse->getAuthCode() . "\n";
                    echo " Charge Customer Profile TRANS ID  : " . $tresponse->getTransId() . "\n";
                    echo " Code : " . $tresponse->getMessages()[0]->getCode() . "\n";
                    echo " Description : " . $tresponse->getMessages()[0]->getDescription() . "\n";
                } else {
                    echo "Transaction Failed \n";
                    if ($tresponse->getErrors() != null) {
                        echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                        echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
                    }
                }
            } else {
                echo "Transaction Failed \n";
                $tresponse = $response->getTransactionResponse();
                if ($tresponse != null && $tresponse->getErrors() != null) {
                    echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
                    echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
                } else {
                    echo " Error code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
                    echo " Error message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";
                }
            }
        } else {
            echo "No response returned \n";
        }

        return $response;
    }

    public function invoiceChargeCC(Request $request) {
        $invoiceID = $request->invoiceID;
        $requestData = $request->all();
// Common setup for API credentials
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(config('services.authorize.login'));
        $merchantAuthentication->setTransactionKey(config('services.authorize.key'));
        if ($request->cnumber) {
            $refId = 'ref' . time();
            // Create the payment data for a credit card
            $creditCard = new AnetAPI\CreditCardType();
            $creditCard->setCardNumber($request->cnumber);
            // $creditCard->setExpirationDate( "2038-12");
            $expiry = $request->card_expiry_year . '-' . $request->card_expiry_month;
            $creditCard->setExpirationDate($expiry);
            $paymentOne = new AnetAPI\PaymentType();
            $paymentOne->setCreditCard($creditCard);
            // Create a transaction
            $transactionRequestType = new AnetAPI\TransactionRequestType();
            $transactionRequestType->setTransactionType("authCaptureTransaction");
            $transactionRequestType->setAmount($request->camount);
            $transactionRequestType->setPayment($paymentOne);
            $request = new AnetAPI\CreateTransactionRequest();
            $request->setMerchantAuthentication($merchantAuthentication);
            $request->setRefId($refId);
            $request->setTransactionRequest($transactionRequestType);
            $controller = new AnetController\CreateTransactionController($request);
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
            if ($response != null) {
                $tresponse = $response->getTransactionResponse();
                if (($tresponse != null) && ($tresponse->getResponseCode() == "1")) {
                    $resultStatus = $this->createCustomerProfile($requestData);
                    //add code to save customer profile
                    //echo "Charge Credit Card AUTH CODE : " . $tresponse->getAuthCode() . "\n";
                    //echo "Charge Credit Card TRANS ID  : " . $tresponse->getTransId() . "\n";
                } else {
                    return redirect()->back()->with('warning', 'Charge Credit Card ERROR :  Invalid response');
                    //echo "Charge Credit Card ERROR :  Invalid response\n";
                }
            } else {
                return redirect()->back()->with('warning', 'Charge Credit Card Null response returned');
                //echo "Charge Credit Card Null response returned";
            }
        } else {
            $paymentProfile = PaymentProfile::find($request->payment_option);
            $response = $this->chargeCustomerProfile($paymentProfile->profile_id, $paymentProfile->payment_profile_id, $request->camount);
        }
        //$response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        if ($response != null) {
            $tresponse = $response->getTransactionResponse();
            if (($tresponse != null) && ($tresponse->getResponseCode() == "1")) {
                $invoiceArr = [];
                $invoiceArr['paid_status'] = 1;
                $invoiceArr['auth_code'] = $tresponse->getAuthCode();
                $invoiceArr['trans_id'] = $tresponse->getTransId();
                $invoiceData = Invoice::where('id', $invoiceID)->first();
                if ($invoiceData->fill($invoiceArr)->save()) {
                    //update payment status of 
                    $invoiceIDs = json_decode($invoiceData->store_invoice_ids, true);
                    StoreInvoice::whereIn('id', $invoiceIDs)->update(['paid_status' => 1, 'auth_code' => $tresponse->getAuthCode(), 'trans_id' => $tresponse->getTransId()]);
                    //send payment status email to store owner
                    $data = [];
                    $data['receiver_name'] = "Hello, " . auth()->user()->name;
                    $data['receiver_message'] = "You have paid your invoice!
We would like to thank you and say it's a pleasure doing business with you. If you have any questions please contact us at <a href='mailto:support@dropshipagent.co'>support@dropshipagent.co</a>. We will get back to you as soon as possible.";
                    $data['sender_name'] = env('MAIL_FROM_NAME');

                    $email_data['message'] = $data;
                    $email_data['subject'] = 'Invoice paid successfully';
                    $email_data['layout'] = 'emails.sendemail';
                    try {
                        Mail::to(auth()->user()->email)->send(new SendMailable($email_data));
                    } catch (\Exception $e) {
                        // Never reached
                    }

                    //send notification to store owner
                    Notification::addNotificationFromAllPanel(auth()->user()->id, 'Invoice paid successfully', helGetAdminID(), $invoiceData->id, 'INVOICE_PAID');

                    //send notification to admin
                    Notification::addNotificationFromAllPanel(helGetAdminID(), 'Invoice (' . $invoiceData->id . ') paid successfully', auth()->user()->id, $invoiceData->id, 'INVOICE_PAID');

                    //send email to admin
                    $data = [];
                    $data['receiver_name'] = "Admin";
                    $data['receiver_message'] = "A user " . auth()->user()->username . " has maid payment for invoice ID :: " . $invoiceData->id;
                    $data['sender_name'] = "DSA Team";

                    $email_data['message'] = $data;
                    $email_data['subject'] = 'New invoice paid :: ' . $invoiceData->id;
                    $email_data['layout'] = 'emails.sendemail';
                    try {
                        Mail::to(env('ADMIN_MAIL_ADDRESS', 'info@dropshipagent.co'))->send(new SendMailable($email_data));
                    } catch (\Exception $e) {
                        // Never reached
                    }
                    return redirect('showinvoiceslog')->with('success', 'Invoice Paid successfully!');
                } else {
                    return redirect()->back()->with('warning', 'Failed to Add , Please try again!');
                }
                //echo "Charge Credit Card AUTH CODE : " . $tresponse->getAuthCode() . "\n";
                //echo "Charge Credit Card TRANS ID  : " . $tresponse->getTransId() . "\n";
            } else {
                return redirect()->back()->with('warning', 'Charge Credit Card ERROR :  Invalid response');
                //echo "Charge Credit Card ERROR :  Invalid response\n";
            }
        } else {
            return redirect()->back()->with('warning', 'Charge Credit Card Null response returned');
            //echo "Charge Credit Card Null response returned";
        }
        die;
        return redirect('/');
    }

}
