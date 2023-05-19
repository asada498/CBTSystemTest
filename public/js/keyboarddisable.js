    // keyboard disable
    $(function(){
        $(document).keydown(function(event){
          var keyCode = event.keyCode;    // keycode
          var ctrlClick = event.ctrlKey;  // Ctrl(true or false)
          var altClick = event.altKey;    // Alt(true or false)
          var obj = event.target;         // object
          // F11
          if(keyCode == 122){
                return true;
          }
          // other key disable
          return false;
  
        });
      });