jQuery(document).ready(function() {
    // console.log('hs.js loaded.');
    var now = Date.now();
    jQuery("time").each(

        function() {
            var t = Date.parse(this.getAttribute("datetime"));
            // console.log(this);
            if(t < now) {
                jQuery(this).addClass('expired');
            }
        }

    );

});
