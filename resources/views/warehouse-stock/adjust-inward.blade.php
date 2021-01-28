@extends('voyager::master')

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('css')
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

@stop

<style>
    .row>[class*=col-] {
        margin-bottom: 10px !important;
    }
    .flex {
        border: none !important;
    }
    .bootstrap-select .btn-default {
        all: unset !important;
        border: none !important;
        padding: 5px !important;
        font-size: 14px !important;

    }
    .bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn) {
        width: 100% !important;
    }

    .bootstrap-select .dropdown-toggle .filter-option-inner-inner {
        all: unset !important;
    }

    h1, h3 {
        color: #3faba4 !important;
    }
</style>


@section('content')

    @include('voyager::alerts')


    <div class="container">



        <form action="{{ route('adjust-inward', $warehouse->id) }}" method="POST">
            @csrf

            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 flex flex-col my-2">

                <div class="-mx-3 md:flex">

                <div class="md:w-1/6">
                    <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-first-name">
                        Adjustment Type
                    </label>
                    <select class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4"
                            id="adjustType" name="adjustment_type">
                        <option value="">Nothing Selected</option>
                        <option value="from_factory">From Factory</option>
                        <option value="from_transfer">From Transfer</option>
                    </select>
                </div>

                <div class="md:w-1/2 mt-5 ml-5">
                    <h1 class="text-3xl">(Inward Stock Adjustment for {{ $warehouse->name }})</h1>
                </div>

                </div>

            </div>



            <div id="fromFactoryBlock">
                <h3 class="text-lg mb-2">From Factory</h3>

                <!-- component -->
                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 flex flex-col my-2">

                    <div class="-mx-3 md:flex mb-6">

                        <div class="md:w-1/4 px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-first-name">
                                Factory Name
                            </label>
                            <input class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                   id="grid-first-name" type="text" placeholder="Main Factory" name="from_factory_name">
                        </div>
                        <div class="md:w-1/2 px-3">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-first-name">
                                Product SKU
                            </label>
                            <select class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3 selectpicker w-full"
                                    data-live-search="true"
                                    name="from_factory_product">
                                <option value="-1">Nothing Selected</option>
                                @forelse($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @empty
                                @endforelse

                            </select>
                        </div>
                        <div class="md:w-1/4 px-3">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-first-name">
                                Product PKD
                            </label>
                            <input class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                   id="grid-first-name" type="date" name="from_factory_pkd">
                        </div>
                        <div class="md:w-1/4 px-3">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-first-name">
                                Total Products ( In Carton )
                            </label>
                            <input class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                   id="grid-first-name" type="number" min="0" value="0" name="from_factory_count">
                        </div>
{{--                        <div class="md:w-1/4 px-3">--}}
{{--                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-first-name">--}}
{{--                                PC/Carton--}}
{{--                            </label>--}}
{{--                            <input class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"--}}
{{--                                   id="grid-first-name" type="number" min="0" value="0" name="from_factory_per_carton">--}}
{{--                        </div>--}}
                        <div class="md:w-1/5">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-first-name">
                                In Date
                            </label>
                            <input class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                   id="grid-first-name" type="datetime-local" name="from_factory_date">
                        </div>
                    </div>

                </div>
            </div>

            <div id="fromTransferBlock">
                <h3 class="text-lg mb-2">From Transfer</h3>

                <!-- component -->
                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 flex flex-col my-2">

                    <div class="-mx-3 md:flex mb-6">

                        <div class="md:w-1/4 px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-first-name">
                                Select Warehouse
                            </label>
                            <select class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                    name="from_transfer_name">
                                <option value="-1">Nothing Selected</option>
                                @forelse($warehouses as $data)
                                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                                @empty
                                @endforelse

                            </select>
                        </div>
                        <div class="md:w-1/2 px-3">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-first-name">
                                Product SKU
                            </label>
                            <select class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3 selectpicker"
                                    data-live-search
                                    name="from_transfer_product">
                                <option value="-1">Nothing Selected</option>
                                @forelse($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @empty
                                @endforelse

                            </select>
                        </div>
                        <div class="md:w-1/4 px-3">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-first-name">
                                Product PKD
                            </label>
                            <input class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                   id="grid-first-name" type="date" name="from_transfer_pkd">
                        </div>
                        <div class="md:w-1/4 px-3">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-first-name">
                                Total Products ( In Carton )
                            </label>
                            <input class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                   id="grid-first-name" type="number" min="0" value="0" name="from_transfer_count">
                        </div>
{{--                        <div class="md:w-1/4 px-3">--}}
{{--                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-first-name">--}}
{{--                                PC/Carton--}}
{{--                            </label>--}}
{{--                            <input class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"--}}
{{--                                   id="grid-first-name" type="number" min="0" value="1" name="from_transfer_per_carton">--}}
{{--                        </div>--}}
                        <div class="md:w-1/5">
                            <label class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2" for="grid-first-name">
                                In Date
                            </label>
                            <input class="appearance-none block w-full bg-grey-lighter text-grey-darker border border-red rounded py-3 px-4 mb-3"
                                   id="grid-first-name" type="datetime-local" name="from_transfer_date">
                        </div>
                    </div>

                </div>
            </div>

            <button onclick="return checkAdjustStock()" type="submit" class="btn btn-success">Adjust Stock</button>


        </form>

    </div>

    <div class="container">

            @if ($errors->any())
                <div class="alert alert-danger bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 flex flex-col my-2">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


    </div>

@stop



@section('javascript')
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <script>

        function checkAdjustStock() {

            if ($('#adjustType').val() == 'from_factory') {

                if ($("select[name='from_factory_product']").val() == -1) {
                    alert('Please select a product!');
                    return false;
                }
            } else if ($('#adjustType').val() == 'from_transfer') {

                if ($("select[name='from_transfer_name']").val() == -1) {
                    alert('Please select a warehouse!');
                    return false;
                }
                if ($("select[name='from_transfer_product']").val() == -1) {
                    alert('Please select a product!');
                    return false;
                }
            } else {
                return false;
            }

        }

        $(document).ready(function() {

            $('#fromFactoryBlock').hide();
            $('#fromTransferBlock').hide();

            $('#adjustType').on('change', function(e) {

                adjustType = $(this).val();

                if (adjustType == 'from_factory') {

                    $('#fromTransferBlock').hide('slow');
                    $('#fromFactoryBlock').show('slow');

                    $("input[name='from_factory_pkd']").prop('required',true);
                    $("input[name='from_factory_count']").prop('required',true);
                    $("input[name='from_factory_date']").prop('required',true);
                    $("input[name='from_factory_per_carton']").prop('required',true);

                    $("input[name='from_transfer_pkd']").prop('required',false);
                    $("input[name='from_transfer_count']").prop('required',false);
                    $("input[name='from_transfer_date']").prop('required',false);
                    $("input[name='from_transfer_per_carton']").prop('required',false);


                } else if (adjustType == 'from_transfer') {

                    $('#fromFactoryBlock').hide('slow');
                    $('#fromTransferBlock').show('slow');

                    $("input[name='from_transfer_pkd']").prop('required',true);
                    $("input[name='from_transfer_count']").prop('required',true);
                    $("input[name='from_transfer_date']").prop('required',true);
                    $("input[name='from_transfer_per_carton']").prop('required',true);

                    $("input[name='from_factory_pkd']").prop('required',false);
                    $("input[name='from_factory_count']").prop('required',false);
                    $("input[name='from_factory_date']").prop('required',false);
                    $("input[name='from_factory_per_carton']").prop('required',false);
                } else {
                    $('#fromFactoryBlock').hide('slow');
                    $('#fromTransferBlock').hide('slow');
                }
            });


        });

    </script>

@stop

