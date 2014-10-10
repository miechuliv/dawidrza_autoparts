/**
 * Created with JetBrains PhpStorm.
 * User: USER
 * Date: 20.11.13
 * Time: 09:41
 * To change this template use File | Settings | File Templates.
 */



function Comments()
{
    this.mouseX = -1;
    this.mouseY = -1;

    this.getMousePosition = function()
    {

        var instance = this;
        $(document).mousemove(function(event) {
            instance.mouseX = event.pageX;
            instance.mouseY = event.pageY;
        });

    };





    this.createCommentBox = function()
    {
        html = '<div style="background-color: white; border: 1px solid black; width:200px;height:100px;position: fixed;top: '+ this.mouseY +';left: '+ this.mouseX +';"></div>';

        return html;
    };

    this.displayCommentBox = function()
    {
        var html = this.createCommentBox();

        console.log(html);
        $('body').prepend(html);
    };

    this.keyListener = function()
    {

        var instance = this;
        $(document).keyup(function(e) {

            if (e.keyCode == 57) {
             //   instance.displayCommentBox();
            }


        });

    };

    this.getMousePosition();
    this.keyListener();




}