@extends('layout')

@section('title')
Dashboard. Subscription. Config
@endsection

@section('content')

<div class="row">
    <div class="col-md-11">
        <h3>
            Subscription Management
        </h3>
        <h4>
            {{$application->name}}
        </h4>
        <h4 style="margin: 40px 0;">
            Current subscription plans:
        </h4>
    </div>
</div>
<style>
  .ng-invalid.ng-dirty {
      border-color: red;
  }
   .ng-valid.ng-dirty{
       border-color: green;
   }

  td {
     width: 250px;
     height: 40px;
  }
</style>



<div class="sub-config" ng-controller="SubscriptionController as subCtl">

    <div  class="save-config alert alert-success alert-dismissable hidden">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        Config successfully saved
    </div>
    <div class="col-sm-10">
      <p>
        <label for="subscription_discount">Discount % </label>
        <input ng-change="subCtl.updateGlobalConfig('subscriptionDiscount')" type="number" min="0" ng-model="subCtl.subscriptionDiscount" id="subscription_discount" />
      </p>
    </div>
    <div class="col-sm-10">
        <p>
            <label for="con-fee">Convinience fee $ </label>
            <input ng-change="subCtl.updateGlobalConfig('conFee')" type="number"  min="0" ng-model="subCtl.conFee" id="con-fee" />
        </p>
    </div>
    <div class="col-sm-10">
        <p>
            <label for="grace-period">Grace Period (days) </label>
            <input ng-change="subCtl.updateGlobalConfig('gracePeriod')" type="number"  min="0" ng-model="subCtl.gracePeriod" id="grace-period" />
        </p>
    </div>
    <table class="table table-hover col-sm-10" >
        <thead>
        <tr>
            <th>User Role</th>
            <th>Status</th>
            <th>Partnership Level</th>
            <th>Monthly price</th>
            <th>Annual price</th>
        </tr>
        </thead>

        <!--  BEGIN candidate  -->
        <tbody ng-repeat="config in subCtl.configs">
        <tr class="active">
            <td><% config . role . label %></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <tr ng-repeat="info in config.partnership">
            <td></td>
            <td><input ng-change="subCtl.updateConfigPrice(config.role.key, this)" type="checkbox" ng-show="info.level" ng-disabled="info.level === 'basic'" ng-checked="info . level && subCtl.configs[$parent.$index].partnership[$index].partnership_status" ng-model = "subCtl.configs[$parent.$index].partnership[$index].partnership_status" /></td>
            <td><% info . level || '-' %></td>
            <td><span ng-hide="subCtl.editingMonth[$parent.$index][$index]" ng-click="subCtl.editPrice($parent.$index, $index, 'month')"><% info.price_month  | currency  %></span>
                <input ng-change="subCtl.updateConfigPrice(config.role.key, this)" class="p-price" ng-blur="subCtl.blurEdit(subCtl.editingMonth, $parent.$index, $index)"
                       ng-show="subCtl.editingMonth[$parent.$index][$index]" type="number" min="0"
                       ng-model="info.price_month" value="" focus-if="subCtl.editingMonth[$parent.$index][$index]"  ng-model-options="{ updateOn: 'blur' }" />
            </td>
            <td><span ng-hide="subCtl.editingYear[$parent.$index][$index]" ng-click="subCtl.editPrice($parent.$index, $index, 'year')"><% info.price_year  | currency  %></span>
                <input  ng-change="subCtl.updateConfigPrice(config.role.key, this)" class="p-price" ng-blur="subCtl.blurEdit(subCtl.editingYear, $parent.$index, $index)"
                       ng-show="subCtl.editingYear[$parent.$index][$index]" type="number" min="0"
                       ng-model="info.price_year" value="" focus-if="subCtl.editingYear[$parent.$index][$index]"  ng-model-options="{ updateOn: 'blur' }" />
            </td>
        </tr>
        </tbody>
        <!-- END candidate    ?-->

    </table>
</div>
@endsection
@section('javascripts')
@parent
<script>
    window.CurrentApplication = JSON.parse('{{ $application->toJson() }}');
</script>
{{ HTML::script(URL::asset('project/js/controllers/subscription/config.js')) }}


@endsection

