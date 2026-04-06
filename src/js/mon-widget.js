import "../scss/mon-widget.scss";

document.addEventListener("DOMContentLoaded", function(){
    console.log("MCS Widgets::Mon Widget script loaded")
});

window.addEventListener("elementor/frontend/init",
    ()=>{
        elementorFrontend.hooks.addAction("frontend/element_ready/mcs_widget.default",
            function(e){
                console.log("MCS Widgets::Mon Widget Elementor ready",e)
            }
        )
    }
);
