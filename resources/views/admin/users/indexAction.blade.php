<?php
if ($tab == "Pending_requests") {
    ?>
    <a href="javascript:void(0)" data-id="{{ $user->id }}" data-val="-1" class="btn btn-block btn-danger btn-sm reject_user">Reject Store</a>
    <a href="javascript:void(0)" data-id="{{ $user->id }}" data-val="1" class="btn btn-block btn-success btn-sm accept_user">Accept Store</a>
    <a href="{{ URL::to('admin/users/profile/' . $user->id ) }}" class="btn btn-primary margin2px" title="Profile"><i class="fa fa-user "></i></a>
    <a href="{{ url('admin/products/index', $user->username) }}" class="btn btn-success margin2px" title="Products"><i class="fa fa-product-hunt"></i></a>
    <?php
}
if ($tab == "Approved_and_Unpaid") {
    ?>
    <a href="{{ URL::to('admin/users/' . $user->id . '/edit') }}" class="btn btn-primary margin2px" title="Edit Product">
        <i class="fa fa-edit"></i>
    </a>
    <a href="{{ url('admin/products/index', $user->username) }}" class="btn btn-success margin2px" title="Products"><i class="fa fa-product-hunt"></i></a>
    <a href="{{ url('admin/users/set_store_session', $user->username) }}" class="btn btn-warning mappedStoreChange" title="Select Store">Proceed</a>
    <?php
}
if ($tab == "Approved_and_Paid") {
    ?>
    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline" onsubmit="return confirm('Are you sure?');">
        <input type="hidden" name="_method" value="DELETE">
        {{ csrf_field() }}
        <button class="btn btn-danger margin2px" title="Delete"><i class="fa fa-trash"></i></button>
    </form>
    <?php
    if ($role == 2) {
        ?>
        <a href="{{ url('admin/users/showcsvlogs', $user->id) }}" class="btn btn-warning  margin2px" title="View Csv Logs"><i class="fa fa-list"></i></a>
        <!--<a href="{{ route('users.show', $user->id) }}" class="btn btn-info margin2px" title="View Detail"><i class="fa fa-eye"></i></a>-->
        <?php if (!isset($user->get_supplier->store_id)) { ?>
            <a href="javascript:void(0);" class="btn btn-warning assign_supplier_btn margin2px" data-id="{{ $user->id }}" data-val="{{ $user->username }}" title="Assign Supplier"><i class="fa fa-bookmark"></i></a>
        <?php } ?>
        <a href="{{ url('admin/products/index', $user->username) }}" class="btn btn-success margin2px" title="Products"><i class="fa fa-product-hunt"></i></a>
        <a href="{{ URL::to('admin/users/' . $user->id . '/edit') }}" class="btn btn-primary margin2px" title="Edit Profile"><i class="fa fa-edit"></i></a>

        <a href="{{ URL::to('admin/users/profile/' . $user->id ) }}" class="btn btn-primary margin2px" title="Profile"><i class="fa fa-user "></i></a>
        <a href="{{ url('admin/users/set_store_session', $user->username) }}" class="btn btn-warning mappedStoreChange" title="Select Store">Proceed</a>
        <?php
    }
}
if ($tab == "Suppliers") {
    echo $supplierStore;
    /*
      ?>
      <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline" onsubmit="return confirm('Are you sure?');">
      <input type="hidden" name="_method" value="DELETE">
      {{ csrf_field() }}
      <button class="btn btn-danger margin2px" title="Delete"><i class="fa fa-trash"></i></button>
      </form>
      <?php
      if ($role == 2) {
      ?>
      <a href="{{ route('users.show', $user->id) }}" class="btn btn-info margin2px" title="View Detail"><i class="fa fa-eye"></i></a>
      <a href="javascript:void(0);" class="btn btn-warning assign_supplier_btn margin2px" data-id="{{ $user->id }}" data-val="{{ $user->username }}" title="Assign Supplier"><i class="fa fa-bookmark"></i></a>

      <?php
      } */
}
?>