<div class="modal fade" id="disiplinModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tüm Disiplin Kayıtlarınızı Toplu Yükleme</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="excelUpload" action="{{route('excelUpload')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                  <div class="form-group">
                      <label>Excel Dosyanız</label>
                      <input type="file" name="file">
                  </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Vazgeç</button>
                <button type="button" class="btn btn-primary font-weight-bold excelUpload">Kaydet</button>
            </div>
        </div>
    </div>
</div>
<style>
    .modalaktivasyon {
        cursor: pointer;
    }
</style>
<div class="modal fade modalaktivasyon" id="LoadingModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">EXCEL YÜKLENİYOR</h5>
            </div>
            <div class="modal-body">
                <h3>Lütfen Bekleyiniz</h3>
                <div class="loader-container">
                    <div class="loader">
                        <div class="square one"></div>
                        <div class="square two"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
