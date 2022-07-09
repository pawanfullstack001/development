@extends('admin.layout.main1')

@section('content')

<style>
  .card .card-body {
    margin: 0 20% !important;
  }
  .clear-rating.clear-rating-active{
display: none !important;
  }
  label{
    font-size: 16px !important;
    font-weight: bold !important;
  }
</style>


<div class="container mt-5">

  @if(!empty(session('message')))
<div class="row">
  <div class="col-md-12">
    <div class="alert alert-success">
      {{session('message')}}
    </div>
  </div>
</div>
@endif
  <div class="row">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title font-weight-bold font-20">Califica tu experiencia de reserva</h5>
        <div class="row">
          <form method="post" action="{{route('booking_feedback',request('booking_id'))}}">
            <div class="form-group">
              <input id="input-21b" name="rating" type="hidden" value="5" type="text" class="rating" data-theme="krajee-fas" data-min=0 data-max=5 data-step=1 data-size="lg" required title="">
              <div class="clearfix"></div>
            </div>
            <div class="form-group">
              <label for="review">Observaciones</label>
              <textarea class="form-control" name="review" id="review" cols="30" placeholder="Escribe aqui..." rows="10"></textarea>

            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>

</div>



<script>
  jQuery(document).ready(function() {
    $("#input-21f").rating({
      starCaptions: function(val) {
        if (val < 3) {
          return val;
        } else {
          return 'high';
        }
      },
      starCaptionClasses: function(val) {
        if (val < 3) {
          return 'label label-danger';
        } else {
          return 'label label-success';
        }
      },
      hoverOnClear: false
    });
    var $inp = $('#rating-input');

    $inp.rating({
      min: 0,
      max: 5,
      step: 1,
      size: 'lg',
      showClear: false
    });

    $('#btn-rating-input').on('click', function() {
      $inp.rating('refresh', {
        showClear: true,
        disabled: !$inp.attr('disabled')
      });
    });


    $('.btn-danger').on('click', function() {
      $("#kartik").rating('destroy');
    });

    $('.btn-success').on('click', function() {
      $("#kartik").rating('create');
    });

    $inp.on('rating.change', function() {
      alert($('#rating-input').val());
    });


    $('.rb-rating').rating({
      'showCaption': true,
      'stars': '3',
      'min': '0',
      'max': '3',
      'step': '1',
      'size': 'xs',
      'starCaptions': {
        0: 'status:nix',
        1: 'status:wackelt',
        2: 'status:geht',
        3: 'status:laeuft'
      }
    });
    $("#input-21c").rating({
      min: 0,
      max: 8,
      step: 0.5,
      size: "xl",
      stars: "8"
    });
  });
</script>




@endsection