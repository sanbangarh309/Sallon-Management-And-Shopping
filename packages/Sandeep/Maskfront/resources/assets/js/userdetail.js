$("#save_personal_info").click(function(event) {
  event.preventDefault();
  var user_fname = $("#user_fname").val();
  var user_lname = $("#user_lname").val();
  var user_email = $("#user_email").val();

  var _sln_phone = $("#user_contact").val();
  var user_address = $("#user_address").val();
  var current_user_id = $("#current_user_id").val();

  var date_of_birth = $("#date_picker_date").val();
  var user_password = $("#user_password").val();
  var user_confpassword = $("#user_confpassword").val();
  var _sln_gender_field = $("#_sln_gender_field").val();
  if (user_password != user_confpassword) {
    $("#_msg_block").show();
    $("#_msg_block").html($('#password_mismatch').val());
    return false;
  }
  var ajax_url = $( "#update_account" ).val();

  var is_avtar = $("#img_avatar").val().length;

  if(is_avtar!=0){

    var form  = new FormData();
    var image = $("#img_avatar")[0].files[0];
    form.append("image", image);
    form.append("user_id", current_user_id);
    //Using localize script to pass "site_url" and "nonce"
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('#csrf_token').val()
      },
      url: ajax_url,
      type: "POST",
      data: form,
      contentType: false,
      processData: false
    }).done(function(data){
      // console.log("data is: ", data);
    }).fail(function(data){
      // console.log("errors are: ", error);
    });

  }

  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('#csrf_token').val()
    },
    type:"POST",
    url: ajax_url,
    data: {
      user_id: current_user_id,
      name: user_fname,
      lname: user_lname,
      email: user_email,
      phone: _sln_phone,
      address: user_address,
      dob: date_of_birth,
      password: user_password,
      confpassword: user_confpassword,
      gender: _sln_gender_field,
    },
    beforeSend: function(){
      $(".loader").show();
    },
    success:function(data){
      if(data["res"]==0){
        $(".loader").hide();
        $("#_msg_block").show();
        $("#_msg_success").hide();
        $("#_msg_block").html(data["msg"]);
      }else if (data["res"]==1){
        $(".loader").hide();
        $("#_msg_block").hide();
        $("#_msg_success").show();
        $("#_msg_success").html(data["msg"]);
      }
    }
  });

});


$("#browse_avatar").click(function () {
  $("#img_avatar").trigger("click");
});

if ($("#img_avatar").length)
{
  document.getElementById("img_avatar").addEventListener("change", handleFileSelect, false);
}

$("#date_picker_date").datepicker({
  dateFormat: "yy-mm-dd",
  yearRange: "-100:+0",
  changeMonth: true,
  changeYear: true,
  inline: true
});

if($("#diamonds_history").length){
  $("#diamonds_history").DataTable();
}

/* Function to render uploaded image and display before uploading */
function handleFileSelect(evt) {
  var files = evt.target.files; // FileList object
  // Loop through the FileList and render image files as thumbnails.
  for (var i = 0, f; f = files[i]; i++) {
    // Only process image files.
    if (!f.type.match('image.*')) {
      continue;
    }
    var reader = new FileReader();
    // document.getElementById('avtr_img').innerHTML='';
    // Closure to capture the file information.
    reader.onload = (function(theFile) {
      return function(e) {
        // Render thumbnail.
        if($('#_avatar_image').length){
          document.getElementById('_avatar_image').setAttribute("src", e.target.result);
        }else{
          document.getElementById('_avatar_image').setAttribute("src", e.target.result);
        }
      };
    })(f);
    // Read in the image file as a data URL.
    reader.readAsDataURL(f);
  }
}

function cancelBooking(bookid,userid){
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('#csrf_token').val()
    },
    type:"POST",
    url: $('#reject_booking').val(),
    data: {
      bookid: bookid,
      userid: userid,
      action: 'user_action',
      status: 'Confirmed'
    },
    beforeSend: function(){
      $(".loader").show();
    },
    success:function(data){
      $('#new div#san_canceled_bookings').html(data.can_html);
    }
  });
}

function cancelOrder(oid,userid){
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('#csrf_token').val()
    },
    type:"POST",
    url: $('#ajax_url').val()+'/cancel_order',
    data: {
      orderid: oid,
      userid: userid
    },
    beforeSend: function(){
      $(".loader").show();
    },
    success:function(data){
      $('#order_history div.order_history_tab').html(data.order_html);
    }
  });
}

/* Rating */
function addRating(obj) {
  removeHighlight();
  var rating = 0;
  $('#product_star_rating li').each(function(index) {
    $(this).addClass('selected');
    rating = rating + 1;
    if(index == $('#product_star_rating li').index(obj)) {
      return false;
    }
  });
  console.log(rating);
  $('#new-review-formm #rating').val(rating);
}
function removeHighlight() {
  $('#product_star_rating li').removeClass('selected');
  $('#product_star_rating li').removeClass('highlight');
}
function highlightStar(obj,id) {
  $('#product_star_rating li').each(function(index) {
    $(this).addClass('highlight');
    if(index == $('#product_star_rating li').index(obj)) {
      return false;
    }
  });
}

function submitReview(crnt){
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('#csrf_token').val()
    },
    type : "POST",
    url  : $('#ajax_url').val()+'/addreview',
    data : $(crnt).serialize(),
    cache : false,
    success  : function(data) {
      if (data.message) {
        swal("",data.message,"error");
      }else{
        swal("","Feedback Given","success");
      }
      if(!data.done){
        $('#leave_feedback').modal('toggle');
      }
      
    },
    error : function(data){
      if (data.message) {
        swal("",data.message,"success");
      }else{
        swal("","Feedback Given","success");
      }
      if(!data.done){
        $('#leave_feedback').modal('toggle');
      }
    }
  });
}
