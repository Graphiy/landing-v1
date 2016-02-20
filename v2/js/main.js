var onNext = function(e){
  if (e.keyCode == 32) Grafiy()
}
jQuery(document).keyup(onNext)
jQuery('#btnNext').click(function(){
  Grafiy()
})

var submit = function(){
  var answer = {email: jQuery("#feedback").val(), feedback: jQuery("#email").val()}
  jQuery.ajax({
    type: 'POST'
  , url: '/request.php'
  //TODO answer: email from field
  , data: {answer: answer, question_id: 0}
  , success : function(data){alert("Thank you for your interest!")}
  , error: function(data){ jQuery('#alert').html(data) }
  })
}
jQuery("#subscribe").on("click", function(){
  submit("email")
})
jQuery("#email")
  .keyup(function (e) {
    if (e.keyCode == 13 && this.value) {
      submit("email")
    }
  })
jQuery("#send").on("click", function(){
  submit("feedback")
})
if (jQuery('#russian')){
  jQuery("#russian").on("click", function(){
    var currentPath = window.location.pathname.replace('/ru', '')
    window.location = "/ru"+currentPath;
  })
  jQuery("#english").on("click", function(){
    var currentPath = window.location.pathname.replace('/ru', '')
    window.location = currentPath;
  })
}
