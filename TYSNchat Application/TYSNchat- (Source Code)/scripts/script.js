$(document).ready(function(){
    //user activity
    update_last_activity();
    function update_last_activity(){
        $.ajax({
            url:"backend/update_last_activity.php",
            success:function(){

            }
        })
    }

    setInterval(function(){
        update_last_activity();
    }, 5000);
})