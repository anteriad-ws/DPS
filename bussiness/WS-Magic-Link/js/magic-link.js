if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
  function validate(event){
    event.preventDefault();
    //Validate each form input
    $("form input[data-required]").each(function(index){
      var $_this = $(this);
      var $_error = $_this.next(".error");
      if($_this.val().length == 0) {
        if($_error.length == 0){
           $_this.after('<p class="error">'+$_this.data("error-message")+'</p>');
        }
      } else
           $_error.remove();
    });
  }
  
  
  /*$("#magicloginform").on({
    "submit": function(){
      validate(event);
    },
    "change": function(){
       validate(event);
    }
  });*/
