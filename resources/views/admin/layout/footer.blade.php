    <!-- START COPYRIGHT -->
    <div class=" container-fluid  container-fixed-lg footer">
      <div class="copyright sm-text-center">
      <p class="small no-margin pull-left sm-pull-reset">
          <span class="hint-text">Copyright &copy; {{date('Y')}} </span>
          <span class="font-montserrat">Phone2Web Spa </span>.
          <span class="hint-text">Todos los derechos reservados. </span>
          
        </p>
        
        <div class="clearfix"></div>
      </div>
    </div>
    <!-- END COPYRIGHT -->
    <!-- START OVERLAY -->
    <div class="overlay hide" data-pages="search">
      <!-- BEGIN Overlay Content !-->
      <div class="overlay-content has-results m-t-20">
        <!-- BEGIN Overlay Header !-->
        <div class="container-fluid">
          <!-- BEGIN Overlay Logo !-->
          <img class="overlay-brand" src="{{  asset('assets/img/logo.png') }}" alt="logo" data-src="{{  asset('assets/img/logo.png') }}" data-src-retina="{{  asset('assets/img/logo_2x.png') }}" width="130" height="30">
          <!-- END Overlay Logo !-->
          <!-- BEGIN Overlay Close !-->
          <a href="#" class="close-icon-light overlay-close text-black fs-16">
            <i class="pg-close"></i>
          </a>
          <!-- END Overlay Close !-->
        </div>
        <!-- END Overlay Header !-->
        <div class="container-fluid">
          <!-- BEGIN Overlay Controls !-->
          <input id="overlay-search" class="no-border overlay-search bg-transparent" placeholder="Search..." autocomplete="off" spellcheck="false">
          <br>
          <div class="inline-block">
            <div class="checkbox right">
              <input id="checkboxn" type="checkbox" value="1" checked="checked">
              <label for="checkboxn"><i class="fa fa-search"></i> Search within page</label>
            </div>
          </div>
          <div class="inline-block m-l-10">
            <p class="fs-13">Press enter to search</p>
          </div>
          <!-- END Overlay Controls !-->
        </div>
        <!-- BEGIN Overlay Search Results, This part is for demo purpose, you can add anything you like !-->
        <div class="container-fluid">
          <span>
                <strong>suggestions :</strong>
            </span>
          <span id="overlay-suggestions"></span>
          <br>
          <div class="search-results m-t-40">
            <p class="bold">Pages Search Results</p>
            <div class="row">
              <div class="col-md-6">
                <!-- BEGIN Search Result Item !-->
                <div class="">
                  <!-- BEGIN Search Result Item Thumbnail !-->
                  <div class="thumbnail-wrapper d48 circular bg-success text-white inline m-t-10">
                    <div>
                      <img width="50" height="50" src="{{  asset('assets/img/profiles/avatar.jpg') }}" data-src="{{  asset('assets/img/profiles/avatar.jpg') }}" data-src-retina="{{  asset('assets/img/profiles/avatar2x.jpg') }}" alt="">
                    </div>
                  </div>
                  <!-- END Search Result Item Thumbnail !-->
                  <div class="p-l-10 inline p-t-5">
                    <h5 class="m-b-5"><span class="semi-bold result-name">ice cream</span> on pages</h5>
                    <p class="hint-text">via john smith</p>
                  </div>
                </div>
                <!-- END Search Result Item !-->
                <!-- BEGIN Search Result Item !-->
                <div class="">
                  <!-- BEGIN Search Result Item Thumbnail !-->
                  <div class="thumbnail-wrapper d48 circular bg-success text-white inline m-t-10">
                    <div>T</div>
                  </div>
                  <!-- END Search Result Item Thumbnail !-->
                  <div class="p-l-10 inline p-t-5">
                    <h5 class="m-b-5"><span class="semi-bold result-name">ice cream</span> related topics</h5>
                    <p class="hint-text">via pages</p>
                  </div>
                </div>
                <!-- END Search Result Item !-->
                <!-- BEGIN Search Result Item !-->
                <div class="">
                  <!-- BEGIN Search Result Item Thumbnail !-->
                  <div class="thumbnail-wrapper d48 circular bg-success text-white inline m-t-10">
                    <div><i class="fa fa-headphones large-text "></i>
                    </div>
                  </div>
                  <!-- END Search Result Item Thumbnail !-->
                  <div class="p-l-10 inline p-t-5">
                    <h5 class="m-b-5"><span class="semi-bold result-name">ice cream</span> music</h5>
                    <p class="hint-text">via fluper.</p>
                  </div>
                </div>
                <!-- END Search Result Item !-->
              </div>
              <div class="col-md-6">
                <!-- BEGIN Search Result Item !-->
                <div class="">
                  <!-- BEGIN Search Result Item Thumbnail !-->
                  <div class="thumbnail-wrapper d48 circular bg-info text-white inline m-t-10">
                    <div><i class="fa fa-facebook large-text "></i>
                    </div>
                  </div>
                  <!-- END Search Result Item Thumbnail !-->
                  <div class="p-l-10 inline p-t-5">
                    <h5 class="m-b-5"><span class="semi-bold result-name">ice cream</span> on facebook</h5>
                    <p class="hint-text">via facebook</p>
                  </div>
                </div>
                <!-- END Search Result Item !-->
                <!-- BEGIN Search Result Item !-->
                <div class="">
                  <!-- BEGIN Search Result Item Thumbnail !-->
                  <div class="thumbnail-wrapper d48 circular bg-complete text-white inline m-t-10">
                    <div><i class="fa fa-twitter large-text "></i>
                    </div>
                  </div>
                  <!-- END Search Result Item Thumbnail !-->
                  <div class="p-l-10 inline p-t-5">
                    <h5 class="m-b-5">Tweats on<span class="semi-bold result-name"> ice cream</span></h5>
                    <p class="hint-text">via twitter</p>
                  </div>
                </div>
                <!-- END Search Result Item !-->
                <!-- BEGIN Search Result Item !-->
                <div class="">
                  <!-- BEGIN Search Result Item Thumbnail !-->
                  <div class="thumbnail-wrapper d48 circular text-white bg-danger inline m-t-10">
                    <div><i class="fa fa-google-plus large-text "></i>
                    </div>
                  </div>
                  <!-- END Search Result Item Thumbnail !-->
                  <div class="p-l-10 inline p-t-5">
                    <h5 class="m-b-5">Circles on<span class="semi-bold result-name"> ice cream</span></h5>
                    <p class="hint-text">via google plus</p>
                  </div>
                </div>
                <!-- END Search Result Item !-->
              </div>
            </div>
          </div>
        </div>
        <!-- END Overlay Search Results !-->
      </div>
      <!-- END Overlay Content !-->
    </div>
    <!-- END OVERLAY -->
  </div>
  <!-- END PAGE CONTENT WRAPPER -->
</div>
<!-- END PAGE CONTAINER -->
<!-- BEGIN VENDOR JS -->
<script src="{{  asset('assets/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{  asset('assets/js/modernizr.custom.js') }}"></script>
<script src="{{  asset('assets/js/jquery-ui.min.js') }}"></script>
<script src="{{  asset('assets/js/popper.min.js') }}"></script>
<script src="{{  asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{  asset('assets/js/jquery.scrollbar.min.js') }}"></script>
<script src="{{  asset('assets/js/bootstrap-datepicker.js') }}"></script>
<script src="{{  asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{  asset('assets/js/iEdit.js') }}"></script>
<script src="{{  asset('assets/js/pages.js') }}"></script>
<script src="{{  asset('assets/js/Chart.min.js') }}"></script>
<!-- <script src="{{  asset('assets/js/scripts.js') }}"></script> -->
<!-- END PAGE LEVEL JS -->
<script type="text/javascript">
  if($('#bar-chart1').length > 0){
    new Chart(document.getElementById("bar-chart1"), {
      type: 'bar',
      borderWidth: 1,
      data: {
        labels: ["Jan", "Feb", "May", "June"],
        datasets: [
          {
            label: "Revenues (millions)",
            backgroundColor: ["#e57e25", "#f29c1f","#14a085","#71c285"],
            data: [478,345,574,514]
          }
        ]
      },
      options: {
        legend: { display: false },
        title: {
          display: true,
          text: ''
        }
      }
    });
  } 

  if($('#bar-chart2').length > 0){
    new Chart(document.getElementById("bar-chart2"), {
      type: 'bar',
      borderWidth: 1,
      data: {
        labels: ["Jan", "Feb", "May", "June"],
        datasets: [
          {
            label: "Revenues (millions)",
            backgroundColor: ["#e57e25", "#f29c1f","#14a085","#71c285"],
            data: [478,345,574,514]
          }
        ]
      },
      options: {
        legend: { display: false },
        title: {
          display: true,
          text: ''
        }
      }
    });
  } 

  if($('#bar-chart3').length > 0){
    new Chart(document.getElementById("bar-chart3"), {
      type: 'pie',
      data: {
        labels: ["Active", "Offline"],
        datasets: [{
          color: ["#fff"],
          // label: "Population (millions)",
          backgroundColor: ["#14a085","#71c285"],
          data: [2478,433],
        }]
      },
      options: {
        title: {
          display: false,
          // text: 'Predicted world population (millions) in 2050'
        }
      }
    });
  } 

  if($('#bar-chart4').length > 0){
    new Chart(document.getElementById("bar-chart4"), {
      type: 'bar',
      borderWidth: 1,
      data: {
        labels: ["Jan", "Feb", "May", "June"],
        datasets: [
          {
            label: "Revenues (millions)",
            backgroundColor: ["#e57e25", "#f29c1f","#14a085","#71c285"],
            data: [478,345,574,514]
          }
        ]
      },
      options: {
        legend: { display: false },
        title: {
          display: true,
          text: ''
        }
      }
    });
  } 



  $(".alert-success").fadeTo(2000, 500).slideUp(500, function(){
    $(".alert-success").slideUp(500);
});

$(".alert-danger").fadeTo(2000, 500).slideUp(500, function(){
    $(".alert-danger").slideUp(500);
});

</script>
</body>
</html>