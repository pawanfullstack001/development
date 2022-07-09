
  
  <div class="contentPage"></div>
      <div class="layout-content right-container" id="right-container">
        <div class="layout-content-body">
          <!-- <div class="row">
            <div class="col-md-6">
              <div class="main-header">
                <h4>Member Management</h4>
              </div>
            </div>
            <div class="col-md-6">
              <ul class="breadcrumb">
                <li><i class="fa fa-home"></i><a href="dashboard.php"> Home</a></li>
                <li class="active">Member Management</li>
              </ul>
            </div>
          </div> -->
          <div class="card edit-profile-page m-0"> 
            <div class="card-body">
              <form class="account_form m-b-15" action="">
                <div class="row">

                  <div class="col-md-12">
                    <div class="pull-left">
                      <h2>Content Management</h2>
                    </div>
                    <div class="add_member pull-right">
                      <button class="btn btn-danger btn-sm" type="button" onclick="loadMap()" data-toggle="modal" data-target="#add-admin">
                        <i class="fa fa-user-plus" aria-hidden="true"></i> Add New Organization
                      </button>
                    </div> 
                  </div>            
                </div>
              </form>
              <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                  <!-- Card -->
                  @if($type==1 && $locale=="en")
                  <div class="card">
                    <div class="card-body">
                      <div class="row gutter-xs">
                        <div class="col-10 cl-sm-12 col-md-12 col-lg-12">
                          <h4 class="bold">About Us
                            <button class="btn btn-primary btn-sm pull-right" data-target="#about-modal" data-toggle="modal"><i class="fa fa-pencil"></i></button>
                          </h4>
                        </div>
                        <div class="col-12 cl-sm-12 col-md-12 col-lg-12 m-t-10">
                          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </p>
                          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </p>
                        </div>
                      </div>
                    </div>
                  </div>
                  @endif
                  <!-- Card -->
                  <!-- Card -->
                  <div class="card">
                    <div class="card-body">
                      <div class="row gutter-xs">
                        <div class="col-10 cl-sm-12 col-md-12 col-lg-12">
                          <h4 class="bold">Terms & Conditions english
                            <button class="btn btn-primary btn-sm pull-right" data-target="#term-modal" data-toggle="modal"><i class="fa fa-pencil"></i></button>
                          </h4>
                        </div>
                        <div class="col-12 cl-sm-12 col-md-12 col-lg-12 m-t-10">
                          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </p>
                          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Card -->
                  <!-- Card -->
                  <div class="card">
                    <div class="card-body">
                      <div class="row gutter-xs">
                        <div class="col-10 cl-sm-12 col-md-12 col-lg-12">
                          <h4 class="bold">Privacy Policy
                            <button class="btn btn-primary btn-sm pull-right" data-target="#privacy-modal" data-toggle="modal"><i class="fa fa-pencil"></i></button>
                          </h4>
                        </div>
                        <div class="col-12 cl-sm-12 col-md-12 col-lg-12 m-t-10">
                          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </p>
                          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Card -->
                  <!-- Card -->
                  <div class="card">
                    <div class="card-body">
                      <div class="row gutter-xs">
                        <div class="col-10 cl-sm-12 col-md-12 col-lg-12">
                          <h4 class="bold">FAQ's
                            <button class="btn btn-primary btn-sm pull-right" data-target="#faqs-modal" data-toggle="modal"><i class="fa fa-pencil"></i></button>
                          </h4>
                        </div>
                        <div class="col-12 cl-sm-12 col-md-12 col-lg-12 m-t-10">
                          <h5>Question 1</h5>
                          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </p>
                          <h5>Question 2</h5>
                          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Card -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="about-modal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">      
            <div class="modal-body">
              <button type="button" data-dismiss="modal" class="close">&times;</button>
              <h5>About Us</h5>
              <form action="#">
                <div class="form-group">
                  <textarea rows="5" class="form-control" placeholder="Enter About Us" resize="false"></textarea>
                </div>
                <div class="text-center">
                  <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Save</button>
                  <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
                </div>
              </form>
            </div>      
          </div>
        </div>
      </div>  
      <!-- Modal -->
      <!-- Modal -->
      <div class="modal fade" id="term-modal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">      
            <div class="modal-body">
              <button type="button" data-dismiss="modal" class="close">&times;</button>
              <h5>Terms & Conditions</h5>
              <form action="#">
                <div class="form-group">
                  <textarea rows="5" class="form-control" placeholder="Enter Terms & Conditions" resize="false"></textarea>
                </div>
                <div class="text-center">
                  <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Save</button>
                  <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
                </div>
              </form>
            </div>      
          </div>
        </div>
      </div>  
      <!-- Modal -->
      <!-- Modal -->
      <div class="modal fade" id="privacy-modal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">      
            <div class="modal-body">
              <button type="button" data-dismiss="modal" class="close">&times;</button>
              <h5>Privacy Policy</h5>
              <form action="#">
                <div class="form-group">
                  <textarea rows="5" class="form-control" placeholder="Enter Privacy Policy" resize="false"></textarea>
                </div>
                <div class="text-center">
                  <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Save</button>
                  <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
                </div>
              </form>
            </div>      
          </div>
        </div>
      </div>  
      <!-- Modal -->
      <!-- Modal -->
      <div class="modal fade" id="faqs-modal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">      
            <div class="modal-body">
              <button type="button" data-dismiss="modal" class="close">&times;</button>
              <h5>FAQ's</h5>
              <form action="#">
                <div class="form-group">
                  <input class="form-control" placeholder="Enter Question"/>
                </div>
                <div class="form-group">
                  <textarea rows="5" class="form-control" placeholder="Enter Answer" resize="false"></textarea>
                </div>
                <div class="text-center">
                  <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">Save</button>
                  <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Cancel</button>
                </div>
              </form>
            </div>      
          </div>
        </div>
      </div>  
      <!-- Modal -->


         