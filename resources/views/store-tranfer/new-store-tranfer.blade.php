@extends('layouts.app')

@section('title')
    Larose | Yêu cầu chuyển kho
@endsection

@section('location')
    <li> Chuyển kho </li>
    <li> Yêu cầu chuyển kho </li>
@endsection

@section('content')
    <div ng-controller="StoreTranferController">

        {{-- Thông tin nhập--}}
        <div class="row">
            <div class="col-sm-4">
                <label> Ngày chuyển dự kiến </label>
                <input ng-model="info.tranfer_date" type="date" class="form-control input-sm">
            </div>
            <div class="col-sm-4">
                <label> Kho chuyển </label>
                <select ng-model="info.store_id" class="form-control input-sm">
                    <option value="" selected> -- Kho chuyển -- </option>
                    <option ng-repeat="store in stores" value="@{{store.id}}"> @{{store.name}} </option>
                </select>
            </div>
            <div class="col-sm-4">
                <label> Kho nhận </label>
                <select ng-model="info.to_store_id" class="form-control input-sm">
                    <option value="" selected> -- Kho nhận -- </option>
                    <option ng-repeat="store in stores" ng-show="store.id != info.store_id" value="@{{store.id}}"> @{{store.name}} </option>
                </select>
            </div>
        </div>
        <h1></h1>
        <label> Lý do chuyển </label>
        <textarea ng-model="info.reason" class="form-control"> </textarea>

        <hr/>

        {{-- NHẬP SẢN PHẨM --}}
        <div class="row">
            <div class="col-lg-4 col-xs-4">
                <button class="btn btn-sm w3-blue-grey" data-toggle="modal" data-target="#chooseProduct"> Chọn SP </button>
                <button class="btn btn-sm w3-blue-grey" data-toggle="modal" data-target="#readReport"> Xem trước </button>
            </div>
            <div class="col-lg-4 col-xs-4">
                <button type="submit" class="btn btn-success btn-sm" ng-click="createStoreTranfer()"> Gửi yêu cầu </button>
                <a href="{{route('list-store-tranfer')}}" class="btn btn-default btn-sm"> Hủy yêu cầu </a>
            </div>
            <div class="col-lg-4 col-xs-4">
                <button class="btn btn-sm w3-blue-grey"> Danh sách: @{{ data.length }} mục </button>
            </div>

        </div>

        <hr/>

        {{-- Danh sách mặt hàng --}}
        <table class="w3-table table-hover table-bordered w3-centered">
            <thead class="w3-blue-grey">
            <th> STT </th>
            <th> Tên </th>
            <th> Mã vạch </th>
            <th> Đơn vị tính </th>
            <th> Số lượng chuyển </th>
            <th> Còn lại </th>
            <th> Giá nhập (VNĐ) </th>
            <th> Hạn sử dụng </th>
            <th> Xóa </th>
            </thead>
            <tbody ng-init="total = 0">
            <tr ng-show="data.length > 0" class="item" dir-paginate="item in data | itemsPerPage: 4">
                <td> @{{ $index+1 }}</td>
                <td> @{{ item.name }} </td>
                <td> @{{ item.code }} </td>
                <td ng-repeat="unit in units" ng-show="unit.id==item.unit_id">
                    @{{ unit.name }}
                </td>
                <td>
                    <input cleave="options.numeral" type="text"
                           ng-model="item.quantity_tranfer" ng-change="checkQuantity(item.quantity_tranfer, item.quantity)"
                           class="form-control input-sm input-numeral">
                </td>
                <td> @{{ item.quantity - item.quantity_tranfer }} </td>
                <td> @{{ item.price | number: 0 }} </td>
                <td> @{{ item.expried_date | date: "dd/MM/yyyy" }} </td>
                <td>
                    <button class="btn btn-sm btn-danger btn-sm" ng-click="delete(item)">
                        <span class="glyphicon glyphicon-trash"></span>
                    </button>
                </td>
            </tr>
            <tr class="item" ng-show="data.length==0">
                <td colspan="9"> Không có dữ liệu </td>
            </tr>
            </tbody>
        </table>

        {{-- PHÂN TRANG --}}
        <div style="margin-left: 35%;">
            <dir-pagination-controls max-size="4"> </dir-pagination-controls>
        </div>

        {{-- Xem biểu mẫu --}}
        <div class="modal fade" id="readReport" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form enctype="multipart/form-data" action="" method="post"> {{csrf_field()}}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title w3-text-blue" id="myModalLabel"> Biểu mẫu </h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-8">
                                    Công ty TNHH Larose <br/>
                                    142 Võ Văn Tân, TP.HCM <br/>
                                    ĐT: 0979369407
                                </div>
                                <div class="col-sm-4">
                                    Số: <br/>
                                    Ngày...tháng...năm...
                                </div>
                            </div>
                            <div class="row">
                                <h2 align="center"> <b> Phiếu chuyển kho </b> </h2>
                                <hr/>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div ng-repeat="store in stores" ng-show="store.id==info.store_id">
                                        Kho chuyển: @{{ store.name }} <br/>
                                        Địa chỉ: @{{ store.address }} <br/>
                                        Số điện thoại: @{{ store.phone }}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div ng-repeat="store in stores" ng-show="store.id==info.to_store_id">
                                        Kho nhận: @{{ store.name }} <br/>
                                        Địa chỉ: @{{ store.address }} <br/>
                                        Số điện thoại: @{{ store.phone }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <h1></h1>
                                    Ngày chuyển dự kiến: @{{info.tranfer_date | date: "dd/MM/yyyy"}}<br/>
                                    Lý do chuyển: @{{ info.reason }}
                                    <h1></h1>
                                </div>
                            </div>
                            <div class="row">
                                <table class="w3-table table-bordered w3-centered">
                                    <thead>
                                    <th> Mã SP </th>
                                    <th> Tên </th>
                                    <th> Mã vạch </th>
                                    <th> Đơn vị tính </th>
                                    <th> Số lượng chuyển </th>
                                    <th> Hạn sử dụng </th>
                                    <th> Giá nhập (VNĐ) </th>
                                    </thead>
                                    <tbody ng-init="total = 0">
                                    <tr ng-show="data.length > 0" class="item" dir-paginate="item in data | itemsPerPage: 4">
                                        <td> @{{ $index+1 }}</td>
                                        <td> @{{ item.name }} </td>
                                        <td> @{{ item.code }} </td>
                                        <td ng-repeat="unit in units" ng-show="unit.id==item.unit_id">
                                            @{{ unit.name }}
                                        </td>
                                        <td> @{{item.quantity_tranfer}} </td>
                                        <td> @{{ item.expried_date | date: "dd/MM/yyyy" }} </td>
                                        <td> @{{ item.price | number: 0 }} </td>
                                    </tr>
                                    <tr class="item" ng-show="data.length==0">
                                        <td colspan="9"> Không có dữ liệu </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <h1></h1>
                            </div>
                            <div class="row">
                                <div class="col-sm-3" align="center">
                                    <b> Quản lý kho chuyển </b><br/> (Ký tên)
                                </div>
                                <div class="col-sm-3" align="center">
                                    <b> Quản lý kho nhận </b><br/> (Ký tên)
                                </div>
                                <div class="col-sm-3" align="center">
                                    <b> Kế toán </b> <br/> (Ký tên)
                                </div>
                                <div class="col-sm-3" align="center">
                                    <b> Người lập phiếu </b> <br/> (Ký tên)
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal"> Đóng </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- CHỌN SẢN PHẨM --}}
        <div class="modal fade" id="chooseProduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title w3-text-blue" id="myModalLabel"> Chọn sản phẩm </h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        <div class="col-sm-8">
                            <input ng-model="term.name" type="text" class="form-control input-sm">
                        </div>
                        <div class="col-sm-4">
                            <select ng-model="info.store_id" class="form-control input-sm" disabled>
                                <option ng-repeat="store in stores" value="@{{store.id}}"> @{{store.name}} </option>
                            </select>
                        </div>
                        </div>
                        <h1></h1>
                        {{-- !DANH SÁCH SẢN PHẨM! --}}
                        <table class="w3-table table-hover table-bordered w3-centered">
                            <thead>
                            <tr class="w3-blue-grey">
                                <th> Mã SP </th>
                                <th> Tên </th>
                                <th> Mã vạch </th>
                                <th> Đơn vị tính </th>
                                <th> Số lượng hiện có </th>
                                <th> Giá mua </th>
                                <th> Hạn sử dụng </th>
                            </thead>
                            <tbody>
                            <tr class="item"
                                ng-show="productInStores.length > 0" dir-paginate="product in productInStores | filter:term | itemsPerPage: 5"
                                ng-click="add(product)" pagination-id="product">
                                <td> @{{$index+1}} </td>
                                <td> @{{ product.name}} </td>
                                <td> @{{ product.code }}</td>
                                <td ng-repeat="unit in units" ng-show="unit.id==product.unit_id">
                                    @{{ unit.name }}
                                </td>
                                <td> @{{ product.quantity | number: 0 }} </td>
                                <td> @{{ product.price | number: 0 }} </td>
                                <td> @{{ product.expried_date | date: "dd/MM/yyyy" }} </td>
                            </tr>
                            <tr class="item" ng-show="products.length==0">
                                <td colspan="7"> Không có dữ liệu </td>
                            </tr>
                            </tbody>
                        </table>
                        <div style="margin-left: 35%;">
                            <dir-pagination-controls pagination-id="product" max-size="4"> </dir-pagination-controls>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

{{-- !ANGULARJS! --}}
@section('script')
    <script src="{{ asset('angularJS/StoreTranferController.js') }}"></script>
@endsection