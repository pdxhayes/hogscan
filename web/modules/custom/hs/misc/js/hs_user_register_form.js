jQuery(document).ready(function() {

  // hide the stupid help message that I can't find the origin of.
  jQuery(".block-help-block").html("");

  // when the hog id field is blurred, verify it is unique
  jQuery("#edit-field-member-hog-id-0-value").blur(

    function() {
      console.log(this.value);
      jQuery.ajax({
        url: "/ajax/unique_hogid?hogid=" + this.value,
        dataType: "json",
        success: function(data) {
          console.log(data);
          if(data.uid) {
            jQuery("#edit-field-member-hog-id-0-value").addClass("error");
            jQuery(".region-highlighted").html(

              '<div role="contentinfo" aria-label="Error message" class="messages messages--error">'
                + '<div role="alert">'
                  + '<h2 class="visually-hidden">Error message</h2>'
                  + 'A member with this National H.O.G. ID already exists.  Please <a href="/user/' + data.uid + '/edit">edit this member record.</a>'
                + '</div>'
              + '</div>'

            );
          } else {
            jQuery("#edit-field-member-hog-id-0-value").removeClass("error");
            jQuery(".region-highlighted").html("");
          }
        }
      });

    }

  );

  // when the email field is blurred, verify it is unique
  jQuery("#edit-mail").blur(

    function() {

      jQuery.ajax({
        url: "/ajax/unique_email?email=" + this.value,
        dataType: "json",
        success: function(data) {
          console.log(data);
          if(data.uid) {
            jQuery("#edit-mail").addClass("error");
            jQuery(".region-highlighted").html(

              '<div role="contentinfo" aria-label="Error message" class="messages messages--error">'
                + '<div role="alert">'
                  + '<h2 class="visually-hidden">Error message</h2>'
                  + 'This email address is in use by a member with the username "'
                  + data.username + '". You can leave this field empty, or enter a unique email address.'
                + '</div>'
              + '</div>'

            );
          } else {
            jQuery("#edit-mail").removeClass("error");
            jQuery(".region-highlighted").html("");
          }
        }
      });

    }

  );


});
