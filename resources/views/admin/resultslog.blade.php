@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Results Log</div>
                <div class="panel-body">
<?php
$field = (new Nayjest\Grids\FieldConfig)
        ->setName('created_at')
        ->setLabel('Created at')
        ->setSortable(true);
$cfg = [
    'src' => 'App\ResultLog',
    'columns' => [
            'id',
            [
                'name'=>'created_at',
                'label'=>'Date',
                'callback'=>function($val){
                    return $val->format('m/d/Y h:i:s A');
                },
                'sortable'=>true,
                
            ],
            [
                'name'=>'applicationId',
                'label'=>'Application ID',
                'sortable'=>true,
                
            ],
            [
                'name'=>'applicationStatus',
                'label'=>'Application Status',
                'sortable'=>true,
                
            ],
            [
                'name'=>'firstName',
                'label'=>'First Name',
                'sortable'=>true,
            ],
            [
                'name'=>'lastName',
                'label'=>'Last Name',
                'sortable'=>true,
            ],
    ],
];
echo Grids::make($cfg);
?>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection