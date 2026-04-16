<?php
use App\Models\Category;
use App\Models\Board;
?>
 <?php 
                            $bd = Board::find($course->board_id);
                            ?>

@extends('frontend.layout.sub-master')
@section('title')
<title>Buy @if($bd)
                            {{$bd->name}} -
                            @endif {{$category->name}} - {{$course->title}} | {{env('APP_NAME')}}</title>
@stop
@section('content')

 <style>
    .fail {
    color: #FF5722;
    font-weight: 700;
}
.success {
    color: #4CAF50;
    font-weight: 700;
}
     .summary{
        background-color: #f1f6fd;
        padding: 5px 21px;
        color: #000;
        border-radius: 13px;
     }
     .course-box {
    border: 1px solid #f1e6e6;
    /* padding: 5px 33px; */
    padding-left: 2em;
    padding-top: 5px;
    padding-bottom: 6px;
    margin-bottom: 12px;
    border-radius: 5px;
    box-shadow: 1px 1px 0px 1px #4a2a51;
    cursor: pointer;
}
.b-t-4-orange{
    border-top: 4px solid #ffbe3d !important;
}
.f-s-12{
    font-size: 12px !important;
}
.f-s-14{
    font-size: 14px !important;
}
.col-718096{
    color: #718096 !important;
}
.cus-gst{
    font-size: 13px !important;
    color: #ec0d0dc2 !important;
    font-weight: 500 !important;   
}
.coupon-forms{
    background: #5cc9a7b5;
    padding: 0px 12px 12px 8px;
    border-radius: 6px;
    margin-bottom: 5px;
    position: relative;
}
.pr-0{
    padding-right: 0px !important;
}
.coupon-p{
        margin-bottom: 4px;
    font-weight: 600;
    font-size: 14px;
    padding: 8px 0px 5px 4px;
}
 </style>
 <main>
     <form method="post">
                            @csrf
            <!-- Section -->
            <section class="section bg-gray-100 border-bottom">
               <div class="container">
                  <div class="row justify-content-center">
                     <div class="col-lg-9 col-xl-8 text-center pb-10 wow fadeInUp pt-8" data-wow-duration="0.5s">
                        <h1 class="h1 mb-3 text-warning">Buy @if($bd)
                            {{$bd->name}} | 
                            @endif {{$category->name}} | {{$course->title}} </h1>
                        
                     </div>
                  </div>
               </div>
            </section>


           @php $coursePrice=0; @endphp

   @if(request('course_mode')=='onetoone_full')
                                         
                                            @php $coursePrice=$course->price_1; @endphp
                                          @elseif(request('course_mode')=='onetoone_monthly') 
                                         
                                             @php $coursePrice=$course->monthly_price_1; @endphp
                                                        @elseif(request('course_mode')=='regular_monthly') 
                                         
                                                            @php $coursePrice=$course->regular_monthly; @endphp
                                                        @elseif(request('course_mode')=='regular_monthly_1') 
                                         
                                                            @php $coursePrice=$course->regular_monthly_1; @endphp
                                          @elseif(request('course_mode')=='onetomany_full') 
                                          
                                             @php $coursePrice=$course->price; @endphp
                                            @elseif(request('course_mode')=='full')
                                            @php $coursePrice=$course->full_price; @endphp
                                            @elseif(request('course_mode')=='quarterly')
                                            @php $coursePrice=$course->quarterly_price; @endphp
                                          @else

   @php $coursePrice=$course->monthly_price; @endphp
                                          @endif

            @php
                $selectedMode = request('course_mode');
                $nonMonthlyModes = ['onetoone_full', 'onetomany_full', 'full', 'quarterly'];
                $isMonthlyMode = $selectedMode === 'onetoone_monthly' || !in_array($selectedMode, $nonMonthlyModes, true);
                $emiDuration = max((int) ($course->duration ?? 0), 0);
                $emiBasePrice = (float) ($coursePrice ?? 0);
            @endphp


            <!-- End Section --><!-- Section -->
            <section class="section pt-0">
               <div class="container mt-n12">
                  <div class="row  align-items-start gy-4">
                        @php $prices = []; $emiPrices = [] @endphp
                        @if(!$purchased_course)
                         @if(count($courses)>1)
                     <div class="col-lg-8 col-xxl-9 wow fadeInUp " data-wow-duration="0.5s">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card shadow-lg b-t-4-orange">
                                   <div class="card-body">
                                    
                                    
                                     
                                      <h5 class="mb-3 pt-2">Other Courses you may also like</h5>
                                      <div class=" mb-3">
                                          <div class="row">
                                            @php $price=0; @endphp
                                          @foreach($courses as $c)
                                          @if(request('course_mode')=='onetoone_full')
                                           @php $prices[] = array("id"=>$c->id,"price"=>round($c->price_1)) @endphp
                                            @php $price=round($c->price_1); @endphp
                                          @elseif(request('course_mode')=='onetoone_monthly') 
                                           @php $prices[] = array("id"=>$c->id,"price"=>round($c->monthly_price_1)) @endphp
                                             @php $price=round($c->monthly_price_1); @endphp
                                                                                    @elseif(request('course_mode')=='onetomany_full') 
                                           @php $prices[] = array("id"=>$c->id,"price"=>round($c->price)) @endphp
                                             @php $price=round($c->price); @endphp
                                                                                    @elseif(request('course_mode')=='regular_monthly')
 @php $prices[] = array("id"=>$c->id,"price"=>round($c->regular_monthly)) @endphp
     @php $price=round($c->regular_monthly); @endphp
                                                                                    @elseif(request('course_mode')=='regular_monthly_1')
 @php $prices[] = array("id"=>$c->id,"price"=>round($c->regular_monthly_1)) @endphp
         @php $price=round($c->regular_monthly_1); @endphp
                                          @else
 @php $prices[] = array("id"=>$c->id,"price"=>round($c->monthly_price)) @endphp
   @php $price=round($c->monthly_price); @endphp
                                          @endif
                                          @php
                                              $emiPrice = $price;
                                              $emiPrices[] = array("id"=>$c->id,"price"=>$emiPrice);
                                          @endphp
                                          @if($c->id!=$course->id)

                                          <?php $purchased_coursex = \Auth::check() && $c->students()->where('user_id', \Auth::id())->count() > 0; ?>
                                          @php $rcount=0 @endphp
                                          @if(!$purchased_coursex && $price>0)
                                          @if($course->duration==$c->duration || str_contains(request('course_mode'),"full") )
                                            @php $rcount++ @endphp
                                          <div class="col-md-6 ">
                                              
                                                <div class="form-check course-box">
                                                  <input class="form-check-input course-select" @if(old('course')) @if(in_array($c->id,old('course'))) checked @endif @endif type="checkbox" value="{{$c->id}}" name="course[]" id="course{{$c->id}}"   >
                                                  <label class="form-check-label" for="course{{$c->id}}">
                                                   <span><strong> {{$category->name}} | {{$c->title}}</strong></span>  <br>
                                                    <span>Fees: <strong class="text-success">₹{{$price}}</strong></span><br>
                                                     @if($c->duration)
                                            <!--<span >-->
                                            <!--    Duration : <strong>{{$c->duration_text}}</strong>-->
                                            <!--    <br>-->
                                            <!--</span>-->
                                            @endif
                                                  </label>
                                                </div>

                                              </div>
                                               @endif
                                               @endif
                                               @endif
                                          @endforeach

                                          @if($rcount==0)
                                          <center><h4>
                                              No Addons course available.
                                          </h4></center>

                                          @endif

                                          </div>
                                       
                                    </div>

                                      
                                      
                                   </div>
                                </div>
                            </div>
                          
                            
                        </div>
                        
                     </div>
                     @else
                      <div class="col-lg-4 col-xxl-3">
                      </div>

                     @endif


                     @else

                       <div class="col-lg-8 col-xxl-9 wow fadeInUp " data-wow-duration="0.5s">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card shadow-lg b-t-4-orange">
                                   <div class="card-body">
                                    
                                    
                                     
                                     <div style="text-align: center;
    margin-top: 15px;">
                                                <h5>You have already purchased this course</h5>

                                                <a href="/user/dashboard" class="btn btn-warning btn-sm">Visit Dashboard</a>
                                            </div>
                                     

                                      
                                      
                                   </div>
                                </div>
                            </div>
                          
                            
                        </div>
                        
                     </div>

                     @endif

                     @if(!$purchased_course)
                        <div class="col-lg-4 col-xxl-3 sticky-lg-top sticky-lg-top-header wow fadeInUp summary b-t-4-orange" data-wow-duration="0.5s">
                            <h4 class="mb-0 pt-2">Order Details</h4>
                            <span>Course Duration: <strong>{{$course->duration_text}} </strong> </span><hr>
                       
                        
                                    
                                    <div class="row pb-2">
                                        <div class="col col-718096">Course Price</div>
                                        <div class="col text-end"><span class="f-s-12"> (+)</span> <span class="fw-bold">₹ {{number_format($coursePrice)}}</span> </div>
                                    </div>
                                    <div class="row pb-2">
                                        <div class="col-md-7 col-718096">Addons Course Price</div>
                                        <div class="col-md-5 text-end"><span class="f-s-12"> (+)</span> ₹ <span class="fw-bold" id="addon_course_price">0</span></div>
                                    </div>
                                    
                                    
                                    
                                    <hr>
                                     <div class="row" id="total_discount_row" >
                                        <div class="col fw-bold">Discount</div>
                                        <div class="col text-end"><span class="f-s-12"> (-)</span>₹ <span class="fw-bold" id="total_discount">0</span></div>
                                    </div> 
                                    <div class="row pb-2">
                                        <div class="col fw-bold">Payable</div>
                                        <div class="col text-end">₹ <span class="fw-bold" id="total_course_price">{{number_format($coursePrice)}}</span></div>
                                    </div>
                                    @if($isMonthlyMode && $emiDuration > 0 && $selectedMode !== 'regular_monthly' && $selectedMode !== 'regular_monthly_1')
                                    <hr>
                                    <div id="emi_details" data-duration="{{$emiDuration}}">
                                        <div class="fw-bold mb-1">Monthly EMI Plan ({{$emiDuration}} Months)</div>
                                        <div id="emi_schedule_list"></div>
                                    </div>
                                    @endif
                                     <!-- <div class="row">
                                        <div class="col cus-gst">Prices are GST Inclusive</div>
                                        <div class="col text-end" style="display:none;"><span class="f-s-12"> (+)</span>₹ <span class="f-s-14 fw-500" id="gst_course_price">{{number_format($coursePrice*0.18)}}</span></div>
                                    </div> -->
                                    <hr>
                                   
                                         
                                        <p class="coupon-p">COUPON CODE</p>
                                        <div class="row">
                                            <div class="col-md-8 pr-0">
                                                <input id="coupon" value="{{request('coupon')}}" name="coupon" style="text-transform:uppercase;" class="form-control" placeholder="Enter code">
                                            </div>
                                            <div class="col-md-4">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-outline-warning mt-1 mb-1" id="applyCoupon">Apply</a>
                                                
                                            </div>
                                             <div class="col-md-12">
                                                <p id="discount_error"></p>
                                             </div>
                                        </div>
                                                                  
                                     
                                    <hr>
                                    <input type="hidden" name="final_price" value="{{$coursePrice}}">
                                    <input type="hidden" name="gst" id="gst_hidden" value="{{$coursePrice - $coursePrice*100/118}}">
                                    <input type="hidden" name="course_id" value="{{$course->id}}">
                                    <input type="hidden" name="course_mode" value="{{request('course_mode')}}">
                                    <div class="row">
                                        <div class="col-md-12 text-center mb-3">
                                           <p class="coupon-p" style="text-align:left;cursor:pointer;">PAYMENT METHOD</p>
                                        <div class="form-check mb-2" style="text-align:left;cursor:pointer;">
  <input class="form-check-input" type="radio" name="payment_method" value="razorpay" id="payment_method1" checked>
  <label class="form-check-label" for="payment_method1" style="font-size:20px;cursor:pointer;">
    <img src="/razorpay_logo.png" style="height: 35px;
    width: 35px;
    border-radius: 6px;"> Razorpay
  </label>
</div>
<!--                     <div class="form-check mb-3" style="text-align:left;cursor:pointer;">-->
<!--  <input class="form-check-input" type="radio" name="payment_method" value="airpay" id="payment_method2">-->
<!--  <label class="form-check-label" for="payment_method2" style="font-size:20px;cursor:pointer;">-->
<!--    <img src="/airpay_india_logo.png" style="height: 35px;-->
<!--    width: 35px;-->
<!--    border-radius: 6px;">  Airpay-->
<!--  </label>-->
<!--</div>-->

                                          <button class="btn payNow btn btn-primary btn-sm btn-block w-75" type="submit">Pay Now</button>
                                          
                                        
                                        </div>
                                    </div> 
                                      
                                    
                               
                            
                        </div>

                        @else

                         <div class="col-lg-4 col-xxl-3 sticky-lg-top sticky-lg-top-header wow fadeInUp summary b-t-4-orange " data-wow-duration="0.5s">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card shadow-lg b-t-4-orange">
                                   <div class="card-body">
                                    
                                    
                                     
                                     <div style="text-align: center;
    margin-top: 15px;">
                                                <h5>You have already purchased this course</h5>

                                                <a href="/user/dashboard" class="btn btn-warning btn-sm">Visit Dashboard</a>
                                            </div>
                                     

                                      
                                      
                                   </div>
                                </div>
                            </div>
                          
                            
                        </div>
                        
                     </div>


                        @endif





                     </div>
                  </div>
               </div>
            </section>
               </form>
            <!-- End Section -->
         </main>
         
         
         <!-- Button trigger modal -->
        <!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Payment Model</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="row justify-content-center">
              <div class="col-md-4">
                  <input type="hidden" name="final_price" value="{{$coursePrice}}">
                                    <input type="hidden" name="gst" id="gst_hidden" value="{{$coursePrice - $coursePrice*100/118}}">
                                    <input type="hidden" name="course_id" value="{{$course->id}}">
                                    <input type="hidden" name="course_mode" value="{{request('course_mode')}}">
                   <!--<a class="btn payNow btn btn-primary btn-sm btn-block w-75" href="javasvript:void(0)">Pay Now</a> -->
                    <button class="btn payNow btn btn-primary btn-sm btn-block " type="submit">Pay With Payment 1</button>
              </div>
              <div class="col-md-4">
                  <button class="btn  btn btn-primary btn-sm btn-block " type="submit">Pay With Payment 2</button>
              </div>
          </div>
     
       
      </div>
      <div class="modal-footer">
       <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <!--<button type="button" class="btn btn-primary">Pay Now</button>-->
      </div>
    </div>
  </div>
</div>



@stop

@section('page_js')

<script>
var selected = <?=$course->id?>;
var selectedEmiPrice = <?=json_encode(round($emiBasePrice, 2))?>;
var allcourses=[];
var courses=<?=json_encode($prices)?>;
var emiCourses=<?=json_encode($emiPrices)?>;
var currentEmiTotal = selectedEmiPrice;


$(document).on('change','.course-select',function(e){
    allcourses=[];
    $("input:checkbox[name='course[]']:checked").each(function(){
    allcourses.push($(this).val());
});
calculatePrice();
// console.log(allcourses)
});

allcourses=[];
    $("input:checkbox[name='course[]']:checked").each(function(){
    allcourses.push($(this).val());
});

calculatePrice();

function calculatePrice(){
   if(allcourses.indexOf(String(selected))===-1 && allcourses.indexOf(selected)===-1){
       allcourses.push(selected);
   }
   var total=0;
   var addonPrice=0;
   var emiTotal=0;
   for(var i=0;i<allcourses.length;i++){
       for(var j=0;j<courses.length;j++){
           if(allcourses[i]==courses[j]["id"]){
               total += parseFloat(courses[j]["price"]); 
                if(selected!=courses[j]["id"]){
               addonPrice += parseFloat(courses[j]["price"]); 
               
           }
           }
          
           
       }
       
   } 
   for(var m=0;m<allcourses.length;m++){
       for(var n=0;n<emiCourses.length;n++){
           if(allcourses[m]==emiCourses[n]["id"]){
               emiTotal += parseFloat(emiCourses[n]["price"]);
           }
       }
   }
   if(emiTotal<=0){
       emiTotal = parseFloat(selectedEmiPrice) || 0;
   }
   currentEmiTotal = emiTotal;
   $("#addon_course_price").html(numberWithCommas(addonPrice));
    $("#total_course_price").html(numberWithCommas(total));
    $("#total_course_price_").html(numberWithCommas(total));
    // $("#gst_course_price").html(numberWithCommas(total*0.18));
      $("#paybale_course_price").html(numberWithCommas(total*0.18+total));
   console.log(total,addonPrice);
    renderEmiSchedule(emiTotal);
    processCoupon();
}
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function parseAmount(value) {
    if (typeof value === 'number') {
        return value;
    }
    return parseFloat(String(value).replace(/,/g, '')) || 0;
}

function renderEmiSchedule(monthlyAmount) {
    var emiContainer = $("#emi_details");
    if (!emiContainer.length) {
        return;
    }

    var duration = parseInt(emiContainer.data("duration"), 10);
    if (!duration || duration < 1) {
        return;
    }

    var perMonth = parseAmount(monthlyAmount);
    var html = '';

    for (var month = 1; month <= duration; month++) {
        html += '<div class="row pb-1">'
            + '<div class="col col-718096">Month ' + month + '</div>'
            + '<div class="col text-end">₹ <span class="fw-bold">' + numberWithCommas(perMonth.toFixed(2)) + '</span></div>'
            + '</div>';
    }

    $("#emi_schedule_list").html(html);
}

 function onSubmit(token) {
       console.log(token);
     document.getElementById("demo-form").submit();
   }
$("#applyCoupon").on("click",function(){
  
    var coupon = $('#coupon').val();
    console.log(coupon);
    processCoupon();
});


function processCoupon(){

 var coupon = $('#coupon').val();

$("#total_discount").html('...');
$("#total_course_price").html('...');
var total_discount = $("#total_discount").html();
var total_course_price = $("#total_course_price").html();
insertParam("coupon",coupon);
 
$.ajax({
    url:'/courses-checkout/apply-coupon',
    type:'GET',
    data:{courses:allcourses,coupon:coupon,_token:'<?=csrf_token()?>',course_mode:'{{request("course_mode")}}'  },
    success:function(res){
        console.log(res);
        if(res.status=='success'){
        if(res.coupon)
        {
            $('#coupon').val(res.coupon);
        } else {
            $('#coupon').val('');
        }

    //          $("#addon_course_price").html(numberWithCommas(addonPrice));
    // $("#total_course_price").html(numberWithCommas(total));
    // $("#total_course_price_").html(numberWithCommas(total));
    // $("#gst_course_price").html(numberWithCommas(total*0.18));
    //   $("#paybale_course_price").html(numberWithCommas(total*0.18+total));
    $("#total_discount").html(numberWithCommas(res.discount));
$("#total_course_price").html(numberWithCommas(res.grant_total));
renderEmiSchedule(res.grant_total);
$("#gst_hidden").val(res.gst);
$("#gst_course_price").html(numberWithCommas(res.gst));
$("input[name='final_price']").val(res.grant_total);


$("#discount_error").removeClass("fail");
$("#discount_error").addClass("success");
$("#discount_error").html(res.html);

        }else{

        $("#total_discount").html(numberWithCommas(res.discount));
$("#total_course_price").html(numberWithCommas(res.grant_total));
renderEmiSchedule(res.grant_total);
$("#gst_hidden").val(res.gst);
$("#gst_course_price").html(numberWithCommas(res.gst));
if(coupon.trim()!=""){
$("#discount_error").removeClass("success");
$("#discount_error").addClass("fail");
$("#discount_error").html(res.html);
}
        }
    },
    error:function(er){
        $("#total_discount").html(total_discount);
        $("#total_course_price").html(total_course_price);
        renderEmiSchedule(currentEmiTotal);
        console.log(er);
    }
});
}


function insertParam(key, value) {
    key = encodeURIComponent(key);
    value = encodeURIComponent(value);

    const url = new URL(window.location.href);
    url.searchParams.delete(key);
url.searchParams.set(key, value);

window.history.replaceState(null, null, url)

}
</script>
   <?php if(Session::has('success')) { ?>

<script>
    $(document).ready(function(){
  
  
  
 
      Swal.fire({
     position: 'top-center',
     icon: 'success',
     title: 'Thank You For Choosing VaaGa Academy.',
     text: '',
     showConfirmButton: false,
     timer: 5500
      })
  
});


</script>
<?php } ?>

@stop