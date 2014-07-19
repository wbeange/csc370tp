/* CSC 370 - Term Project
 * March 15th, 2012
 */

//Create flight route helper. Switches arrival / departure times.
$(document).ready(function() {
  $("#f_time_label2").hide();

  $(":radio[name='f_type']").click(function(){
    switch($(this).attr("id")){
      case "f_typei": $("#f_time_label1").show(); $("#f_time_label2").hide(); break;
      case "f_typeo": $("#f_time_label2").show(); $("#f_time_label1").hide(); break;
    }
  });
});

//Create flight instance helper. Switches arrival / departure dates.
$(document).ready(function() {
  $("#if_date_label2").hide();

  $(":radio[name='if_type']").click(function(){
    switch($(this).attr("id")){
      case "if_typea": $("#if_date_label1").show(); $("#if_date_label2").hide(); break;
      case "if_typed": $("#if_date_label2").show(); $("#if_date_label1").hide(); break;
    }
  });
});

//Class options helper.
$(document).ready(function() {
  $("div.t_class_f").hide();
//  $("div.t_class_r").hide();
  $("div.t_class_s").hide();
  $("div.t_class_i").hide();

  $(":radio[name='t_class']").click(function() {
    //reset...
    $("div.t_class_f").hide();
    $("div.t_class_r").hide();
    $("div.t_class_s").hide();
    $("div.t_class_i").hide();

    switch($(this).attr("id")){
      case "t_classf": $("div.t_class_f").show(); break;
      case "t_classr": $("div.t_class_r").show(); break;
      case "t_classs": $("div.t_class_s").show(); break;
      case "t_classi": $("div.t_class_i").show(); break;
    }
  });
});

//Add airline.
$(function() {  
  $(".add_al_button").click(function() {  

    var al_name = $('input#al_name').val();
    var al_code = $('input#al_code').val();
    var al_url  = $('input#al_url').val();

    var dataString =
      'action=add_airline'+
      '&al_name='+al_name+
      '&al_code='+al_code+
      '&al_url='+al_url;

    $.ajax({
      url: "../handler.php",
      data: dataString,
      dataType: "json",
      success: function( msg ) {
        alert( msg.status );
      },
    });
  });
  return false;  
});

//Add plane model.
$(function() {  
  $(".add_pm_button").click(function() {  

    var pm_code    = $('input#pm_code').val();
    var pm_capacity = $('input#pm_capacity').val();

    var dataString =
      'action=add_plane_model'+ 
      '&pm_code=' +pm_code+
      '&pm_capacity='+pm_capacity;

    $.ajax({
      url: "../handler.php",
      data: dataString,
      dataType: "json",
      success: function( msg ) {
        alert( msg.status );
      },
    });
  });
  return false;  
});

//Add flights :P
$(function() {  
  $(".add_f_button").click(function() {  

    var f_type = $("input[name=f_type]:checked").val();

    var f_source = $('input#f_source').val();
    var f_dest   = $('input#f_dest').val();
    var f_time   = $('input#f_time').val();
    var f_aname  = $('input#f_aname').val();
    var f_pmcode = $('input#f_pmcode').val();

    if(f_type == 'incoming') {
      var action = 'add_incoming_flight';
    } else {
      var action = 'add_departing_flight';
    }

    var dataString =
      'action='+action+ 
      '&f_source='+f_source+
      '&f_dest='+f_dest+
      '&f_time='+f_time+
      '&f_aname='+f_aname+
      '&f_pmcode='+f_pmcode;

    $.ajax({
      url: "../handler.php",
      data: dataString,
      dataType: "json",
      success: function( msg ) {
        alert( msg.status );
      },
    });
  });
  return false;  
});

//Create Passenger.
$(function() {  
  $(".add_passenger_button").click(function() {  

    var p_fname = $('input#p_fname').val();
    var p_lname = $('input#p_lname').val();
    var p_c     = $('input#p_c').val();
    var p_dob   = $('input#p_dob').val();
    var p_pob   = $('input#p_pob').val();

    var dataString =
      'action=add_passenger'+
      '&p_fname='+p_fname+
      '&p_lname='+p_lname+
      '&p_c='+p_c+
      '&p_dob='+p_dob+
      '&p_pob='+p_pob;

    $.ajax({
      url: "../handler.php",
      data: dataString,
      dataType: "json",
      success: function( msg ) {
        alert( msg.status );
      },
    });
  });
  return false;  
});

//Add Flight Instance.
$(function() {  
  $(".add_if_button").click(function() {  

    var if_type = $("input[name=if_type]:checked").val();

    var if_flightnum = $('input#if_flightnum').val();
    var if_gate      = $('input#if_gate').val();
    var if_date      = $('input#if_date').val();

    if(if_type == 'arriving') {
      var action = 'add_arriving';
    } else {
      var action = 'add_departing';
    }

    var dataString =
      'action='+action+
      '&if_gate='+if_gate+
      '&if_date='+if_date+
      '&if_flightnum='+if_flightnum;

    $.ajax({
      url: "../handler.php",
      data: dataString,
      dataType: "json",
      success: function( msg ) {
        alert( msg.status );
      },
    });
  });
  return false;  
});

//Create Ticket.
$(function() {  
  $(".add_t_button").click(function() {  

    var t_bags = $('input#t_bags').val();
    var t_gate = $('input#t_gate').val();
    var t_pid  = $('input#t_pid').val();
    var t_fnum = $('input#t_fnum').val();
    var t_date = $('input#t_date').val();

    var t_class = $("input[name=t_class]:checked").val();

    var t_cocktail = $('input#t_cocktail').val();
    var t_coupons = $('input#t_coupons').val();
    var t_cinfo = $('input#t_cinfo').val();
    var t_babychair = $("input[name=t_class_i_babychair]:checked").val();

    var dataString =
      'action=add_ticket'+
      '&t_bags='+t_bags+
      '&t_gate='+t_gate+
      '&t_pid='+t_pid+
      '&t_fnum='+t_fnum+
      '&t_date='+t_date+
      '&t_class='+t_class+
      '&t_cocktail='+t_cocktail+
      '&t_coupons='+t_coupons+
      '&t_cinfo='+t_cinfo+
      '&t_babychair='+t_babychair;

    $.ajax({
      url: "../handler.php",
      data: dataString,
      dataType: "json",
      success: function( msg ) {
        alert( msg.status );
      },
    });
  });
  return false;  
});

//Delete example form.
$(function() {  
  $(".del_button").click(function() {  

    var aname = $('input#del_aname').val();

    var dataString =
      'action=del_form&aname='+aname;

    $.ajax({
      url: "../handler.php",
      data: dataString,
      dataType: "json",
      success: function( msg ) {
        alert( msg.status );
      },
    });
  });
  return false;  
});

//4A.
$(function() {  
  $(".get_4a_button").click(function() {  

    var aname = $('input#4a_aname').val();
    var place = $('input#4b_place').val();
    var time  = $('input#4c_time').val();

    var dataString = 'action=get4ab&aname='+aname+'&place='+place+'&time='+time;

    $.ajax({
      url: "../handler.php",
      data: dataString,
      dataType: "json",
      success: function( msg ) {
        if( msg.status != 'OK.' )
        {
          alert( msg.status );
        }
        else
        {
          $('.4a_title1').append( msg.title1 );
          $('.4a_title2').append( msg.title2 );
          $('.4a_incomings').append( msg.incomings );
          $('.4a_outgoings').append( msg.outgoings );
          $('.4a_result').show();
        }
      },
    });
  });
  return false;  
});

//4D.
$(function() {  
  $(".get_4d_button").click(function() {  

    var type = $("input[name=4d_type]:checked").val();
    var fnum = $('input#4d_flightnum').val();
    var gnum = $('input#4d_gate').val();
    var date = $('input#4d_time').val();
    var pid  = $('input#4e_pid').val();

    var dataString = 'action=get4d&fnum='+fnum+'&gnum='+gnum+'&date='+date+'&type='+type+'&pid='+pid;

    $.ajax({
      url: "../handler.php",
      data: dataString,
      dataType: "json",
      success: function( msg ) {
        if( msg.status != 'OK.' )
        {
          alert( msg.status );
        }
        else
        {
          $('.4d_title').append( msg.title );
          $('.4d_passengers').append( msg.p );
          $('.4d_result').show();
        }
      },
    });
  });
  return false;  
});
