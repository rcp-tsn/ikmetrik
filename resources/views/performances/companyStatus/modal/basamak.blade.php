<style>
    label
    {
        font-size: 15px;
        font-weight: bold;
    }
    #minMaas
    {
        font-size: 15px;
        font-weight: bold;
    }
    #maxMaas
    {
        font-size: 15px;
        font-weight: bold;
    }
    #toplamPuan
    {
        font-size: 15px;
        font-weight: bold;
    }
</style>
<div class="modal fade" id="BasamakModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> Basamak Personel Sorgula</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label style="font-weight: bold;font-size: 15px;">Basamak</label>
                            {!! Form::select('steps',$steps, null,['class'=>'form-control selectpicker','data-live-search'=>'true','id'=>'step_id']) !!}
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label style="font-weight: bold;font-size: 15px;">Pozisyon</label>
                            {!! Form::text('pozisyon',null,['class'=>'form-control','readonly','id'=>'pozisyonBasamak']) !!}
                        </div>
                    </div>
                </div>
                <br>

                    <table class="table" id="SonucBasamak">
                        <thead>
                        <th style="font-weight: bold;font-size: 15px">Adı Soyadı</th>
                        <th style="font-weight: bold;font-size: 15px">Departman</th>
                        <th style="font-weight: bold;font-size: 15px">Maaş</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary font-weight-bold Basamaksorgula">SORGULA</button>
            </div>
        </div>
    </div>
</div>
