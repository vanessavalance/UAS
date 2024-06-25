@extends('layouts.main')
@section('content')

<section class="banner_area">
    <div class="banner_inner d-flex align-items-center">
        <div class="container">
            <div class="row fullscreen justify-content-center align-items-center">
                <div class="col-lg-7 mt-25">
                    <div class="banner_content">
                        <br>
                        <div class="card">
                            <div class="card-body mx-3">
                                <h5 class="card-title">Where to ?</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Hotel or properties around the world...</h6>
                                <form method="get">
                                    <div class="form-group">
                                        <input id="Destination" type="text" class="form-control" name="Destination"  autocomplete="Destination" placeholder="e.g Grand Aston Hotel" autofocus>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="input-group input-daterange" >
                                            <input id="DtChkIn" type="text" class="form-control clickable input-sm" placeholder="&#xf133;  Check-In">
                                                <span class="input-group-text">to</span>
                                                <input id="DtChkOut" type="text" class="form-control clickable input-sm"  placeholder="&#xf133;  Check-Out">

                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-3 offset-9">
                                            <button type="submit" class="btn btn-primary btn-sm">Search</button>
                                        
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 mt-3">
                    <div class="banner-content text-center mt-5">
                    <img src="img/bannerweb.svg" alt="" class="mb-0">
                </div>
            </div>
        </div>
    </div>
</section>

@endsection