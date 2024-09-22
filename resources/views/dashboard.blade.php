@extends('layouts.template')
@push('page-css')
    <link rel="stylesheet" type="text/css" href="{{asset('src/plugins/switchery/switchery.min.css')}}"/>
@endpush
@section('content')
<div class="xs-pd-20-10 pd-ltr-20">
    <div class="title pb-20">
        <h2 class="h3 mb-0">Dashboard Overview</h2>
    </div>

    <div class="row pb-10">
        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-dark">75</div>
                        <div class="font-14 text-secondary weight-500">
                            Appointment
                        </div>
                    </div>
                    <div class="widget-icon">
                        <div class="icon" data-color="#00eccf" style="color: rgb(0, 236, 207);">
                            <i class="icon-copy dw dw-calendar1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-dark">124,551</div>
                        <div class="font-14 text-secondary weight-500">
                            Total Patient
                        </div>
                    </div>
                    <div class="widget-icon">
                        <div class="icon" data-color="#ff5b5b" style="color: rgb(255, 91, 91);">
                            <span class="icon-copy ti-heart"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-dark">400+</div>
                        <div class="font-14 text-secondary weight-500">
                            Total Doctor
                        </div>
                    </div>
                    <div class="widget-icon">
                        <div class="icon">
                            <i class="icon-copy fa fa-stethoscope" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-dark">$50,000</div>
                        <div class="font-14 text-secondary weight-500">Earning</div>
                    </div>
                    <div class="widget-icon">
                        <div class="icon" data-color="#09cc06" style="color: rgb(9, 204, 6);">
                            <i class="icon-copy fa fa-money" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="title pb-20">
                <h2 class="h3 mb-0">2FA - Verification</h2>
                <div class="mt-2">
                    <input type="checkbox" {{auth()->user()->google2fa_secret?'checked':''}} id="js-switch" class="js-switch handle2fa" data-color="#0099ff" />
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection

@push('scripts')
    <script src="{{asset('src/plugins/switchery/switchery.min.js')}}"></script>
    <script>
        var element = document.getElementById('js-switch');
        var switchery = new Switchery(element);

        $(document).on('change','.handle2fa',function(){
                if(this.checked){
                    var header=modalHeader('Enable 2Factor');
                    var body=`
                        <div class="text-center">
                            <div class="spinner-border spinner-border-md text-dark" role="status">
                            </div>
                        </div>
                    `;
                    modal(header,body,'','modal-lg');
                    $.ajax({
                        url:'enable-2fa',
                        datatype:'json',
                        method:"post",
                        success:function(response){
                            if(response.status==true){
                                var body=`
                                    <div class="text-center">
                                        <img src="${response.data.qr}" />
                                        <label>Please scan this Qr Code By Google Authenticator App. Please Click on verify is scanned.</label>
                                    </div>
                                    `;
                                $('#main-modal-body').html(body);
                                $('#main-modal-footer').html(`
                                    <button type="button" class="btn btn-outline-secondary" onclick="Cancel2FaVerify()">Cancel</button>
                                    <button type="button" class="btn btn-dark" onclick="Verify2Fa(this,'${response.data.secretKey}')">Verify 2Fa</button>
                                `);
                            }
                        },
                        error: function(xhr, status, error) {
                            
                        }
                    });
                }else{
                    alert('Under Development');
                }
            });

            window.Cancel2FaVerify = function(){
                $('#main-modal').modal('hide');
                window.location.reload();
            }

            window.Verify2Fa=function(btn,secretKey){
                btn.disabled=true;
                $.ajax({
                    url:'verify-2fa',
                    datatype:'json',
                    method:"POST",
                    data:{secretKey},
                    success:function(response){
                        btn.disabled=false;
                        $('#main-modal').modal('hide');
                        swal({
                            type: 'success',
                            title: 'Verify 2fa',
                            text: '2-factor verification added successfully.',
                        });
                    }
                })
            }
    </script>
@endpush