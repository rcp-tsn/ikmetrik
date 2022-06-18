            <div class="d-flex flex-column-fluid">
    <!--begin::Container-->
                 <div class="container-fluid">
                     <div class="card card-custom card-transparent">
                            <div class="card-body">
                                <div class="row">

                                </div>
                                @php $soru = 1; @endphp
                        @foreach($questions as  $question)
                                <div class="form-group">
                                    <h4>Soru:    {{$soru++}}</h4>
                                </div>

                                <div class="form-group">
                                    <label  class="control-label">{{$question->question}} ? </label>
                                    <div class="form-group" style="margin-left: 15px">
                                        <div class="radio-list mt-5">
                                            <label class="radio">
                                                <input type="radio" name="radios1">
                                                <span></span>Yetersiz</label>
                                            <label class="radio ">
                                                <input type="radio"  name="radios1">
                                                <span></span>Orta</label>
                                            <label class="radio">
                                                <input type="radio"  name="radios1">
                                                <span></span>İyi</label>
                                            <label class="radio">
                                                <input type="radio"  name="radios1">
                                                <span></span>Mükemmel</label>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                        @endforeach
                            </div>
                     </div>
                 </div>
            </div>

                @section('js')
                    <script>
                        function clickRadio(elmnt) {
                            var n, i, x;
                            n = elmnt.id;
                            for (i = 1; i < 5; i++) {
                                x = document.getElementById("label" + i);
                                if (x) {x.className = x.className.replace(" checkedlabel", "");}
                            }
                            document.getElementById("label" + n).className += " checkedlabel";
                        }
                        function clickNextButton(n) {
                            submitAnswer(n + 1);
                        }
                        function submitAnswer(n) {
                            var f = document.getElementById("quizform");
                            f["nextnumber"].value = n;
                            f.submit();
                        }
                        function startTimer() {
                            var tobj = document.getElementById("timespent")
                            var t = "0:10";
                            var s = 10;
                            var d = new Date();
                            var timeint = setInterval(function () {
                                s += 1;
                                d.setMinutes("0");
                                d.setSeconds(s);
                                min = d.getMinutes();
                                sec = d.getSeconds();
                                if (sec < 10) sec = "0" + sec;
                                document.getElementById("timespent").value = min + ":" + sec;
                            }, 1000);
                            tobj.value = t;
                        }
                        if (window.addEventListener) {
                            window.addEventListener("load", startTimer);
                        } else if (window.attachEvent) {
                            window.attachEvent("onload", startTimer);
                        }

                    </script>
                @endsection
