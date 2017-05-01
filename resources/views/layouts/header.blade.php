<div class="w3-bar w3-small w3-blue-grey">
    <button class="w3-bar-item w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open()">
        <span class="glyphicon glyphicon-align-justify"></span>
    </button>
    <a class="w3-button w3-hover-none w3-hover-text-light-grey"> Larose </a>

    {{-- Nếu là nhân viên hiện chức năng --}}
    @if(!Auth::guest())
    <div class="w3-dropdown-hover w3-right">
        <button class="w3-button"> {{Auth::user()->name}} <span class="caret"></span> </button>
        <div class="w3-dropdown-content w3-bar-block w3-card-2">
            <a href="#" class="w3-bar-item w3-button" data-toggle="modal" data-target="#changePass"> Đổi mật khẩu </a>
            <a href="#" class="w3-bar-item w3-button" data-toggle="modal" data-target="#logout"> Đăng xuất </a>
        </div>
    </div>

    {{-- Hiển thị trạng thái đơn hàng --}}
    <div class="w3-dropdown-hover w3-right" ng-controller="HomeController">
        <button class="w3-button">  Trạng thái dơn hàng <span class="caret"></span> </button>
        <div class="w3-dropdown-content w3-bar-block w3-card-4" style="padding: 10px">
            <label> Đơn hàng chờ duyệt </label>
            <div class="progress">
                <div class="progress-bar progress-bar-striped active" role="progressbar" style="width:@{{getTotalOrderNotConfirmed()}}%">
                    @{{ getTotalOrderNotConfirmed() | number:0 }} %
                </div>
            </div>
            <label> Đơn hàng đang giao </label>
            <div class="progress">
                <div class="progress-bar progress-bar-warning progress-bar-striped active" role="progressbar" style="width:@{{getTotalOrderShipping()}}%">
                    @{{ getTotalOrderShipping() | number:0 }} %
                </div>
            </div>
            <label> Đơn hàng đã giao </label>
            <div class="progress">
                <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" style="width:@{{getTotalOrderShipped()}}%">
                    @{{ getTotalOrderShipped() | number: 0 }} %
                </div>
            </div>
        </div>
    </div>
    @endif
    <a class="w3-button w3-hover-none w3-hover-text-light-grey w3-right"> Web Online </a>
</div>

{{-- ĐỔI MẬT KHẨU --}}
<div id="changePass" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title w3-text-blue"> Đổi mật khẩu </h4>
            </div>
            <form class="form-horizontal" action="{{route('changePassword')}}" method="POST"> {{ csrf_field() }}
            <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-4"> Mật khẩu cũ </label>
                        <div class="col-sm-8">
                            <input name="old_pass" type="password" class="form-control input-sm" placeholder="Nhập mật khẩu cũ...">
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group">
                        <label class="col-sm-4"> Mật khẩu mới </label>
                        <div class="col-sm-8">
                            <input name="new_pass" type="password" class="form-control input-sm" placeholder="Nhập mật khẩu mới...">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4"> Xác nhận mật khẩu </label>
                        <div class="col-sm-8">
                            <input name="confirm_pass" type="password" class="form-control input-sm" placeholder="Xác nhận mật khẩu...">
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info"> Xác nhận </button>
                <button type="button" class="btn btn-default" data-dismiss="modal"> Hủy </button>
            </div>
            </form>
        </div>
    </div>
</div>

{{-- ĐĂNG XUẤT --}}
<div id="logout" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title w3-text-red"> @lang('header.logout') </h4>
            </div>
            <div class="modal-body">
                <h5> @lang('header.warn') </h5>
            </div>
            <div class="modal-footer">
                <a class="btn btn-danger" href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    @lang('header.submit')
                </a>
                <form id="logout-form" action="{{route('logout')}}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
                <button type="button" class="btn btn-default" data-dismiss="modal"> @lang('header.cancel') </button>
            </div>
        </div>
    </div>
</div>