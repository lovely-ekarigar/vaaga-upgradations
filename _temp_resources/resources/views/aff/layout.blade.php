
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="Live Tutorials">
    <link rel="icon" href="https://livetutorials.in/ltlogo.png" type="image/x-icon">
    <link rel="shortcut icon" href="https://livetutorials.in/ltlogo.png" type="image/x-icon">
    <title>@yield('title') Livetutorials Affiliate</title>
    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
     <!-- BEGIN: Vendor CSS-->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="/aff/css/vendors.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="/aff/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/aff/css/bootstrap-extended.min.css">
    <link rel="stylesheet" type="text/css" href="/aff/css/colors.min.css">
    <link rel="stylesheet" type="text/css" href="/aff/css/components.min.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="/aff/css/vertical-menu-modern.css">
    <link rel="stylesheet" type="text/css" href="/aff/css/palette-gradient.min.css">
    <link rel="stylesheet" type="text/css" href="/aff/css/jquery-jvectormap-2.0.3.css">
    <link rel="stylesheet" type="text/css" href="/aff/css/morris.css">
    <link rel="stylesheet" type="text/css" href="/aff/css/style.min.css">
    <link rel="stylesheet" type="text/css" href="/aff/css/palette-gradient.min.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="/aff/css/style.css">
    <!-- END: Custom CSS-->

  <style type="text/css">.keyword-info-container {
  box-sizing: border-box;
  width: 100%;
  margin-bottom: 20px;
  font-size: 12px;
  border-bottom: 1px solid #dee1e5;
}

.keyword-info-container.youtube {
  margin-bottom: 0;
  border-bottom: none;
}

.keyword-info-container .title {
  color: #26282d;
  font-size: 17px;
  font-weight: bold;
}

.keyword-info-container .tabs {
  list-style: none;
  display: flex;
  justify-content: flex-start;
  border-bottom: 1px solid #dee1e5;
  margin-top: -10px;
  padding: 0px 16px;
  align-items: center;
}
.keyword-info-container .tabs li {
  padding: 8px;
  padding-left: 0;
  color: #000;
  cursor: pointer;
  font-size: 12px;
}
.keyword-info-container .tabs li.small {
  font-size: 10px;
}
.keyword-info-container .tabs li:last-child {
  overflow: hidden;
}

.keyword-info-container .tabs li.active {
  color: #4285f4;
}

table.keyword-info-table {
  border-collapse: collapse;
  width: 100%;
  color: #000;
  font-size: 12px;
  position: relative;
}

.keyword-info-table thead {
  height: 50px;
}

.keyword-info-table th {
  padding: 10px;
  padding-left: 0;
  font-weight: bold;
  color: #000000;
  font-size: 12px;
}

.keyword-info-table th:first-child {
  padding-left: 16px;
}

.keyword-info-table th:last-child {
  padding-right: 16px;
}

.keyword-info-table td {
  border-bottom: 1px solid #dee1e5;
  padding: 10px;
  padding-left: 0;
}

.keyword-info-table td:first-child {
  padding-left: 16px;
}

.keyword-info-table td:last-child {
  padding-right: 16px;
}

.keyword-info-table tfoot tr {
  background-color: #dee1e544;
}

.keyword-info-table tfoot tr:last-child td {
  border-bottom: none;
}

.ubersuggest-button {
  color: #0086f7;
  font-family: Arial;
  font-size: 14px;
  font-weight: bold;
  line-height: 29px;
  padding: 8px 30px;
  border: 1px solid #0086f7;
  background-color: #ffffff;
  border-radius: 2px;
  outline: none;
  border: none;
  cursor: pointer;
  margin: 4px;
}

.ubersuggest-logo-wrapper {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  margin: 10px 10px 0 0;
  font-weight: bold;
  color: #26282d;
}

.ubersuggest-logo {
  width: 182px;
  height: 33px;
  cursor: pointer;
}

.keyword-info-container .row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin: 0;
  padding: 20px 16px;
  border-top: 1px solid #dee1e5;
}

.header h2 {
  color: #000000;
  font-family: Geomanist;
  font-size: 24px;
  font-weight: 500;
}

/*
Youtube Dark theme styling
*/
html[dark] .keyword-info-container .title {
  color: #fff;
}

html[dark] .keyword-info-container .tabs {
  border-color: #ffffff0d;
}

html[dark] .keyword-info-container .tabs li {
  color: #fff;
}

html[dark] .keyword-info-container .tabs li.active {
  color: #4285f4;
}

html[dark] table.keyword-info-table {
  color: #fff;
}

html[dark] .keyword-info-table th {
  color: #fff;
}

html[dark] .keyword-info-table tfoot tr {
  background-color: #3d3d3d;
}

html[dark] .keyword-info-table tfoot tr:last-child td .button-arrow {
  border-color: #fff;
}

.keyword-info-table tfoot tr:last-child td .button-arrow.disabled {
  border-color: #9b9b9b !important;
}

/*
Google Dark theme styling
*/

body[data-dt='1'] .keyword-info-container .title {
  color: #fff;
}

body[data-dt='1'] .keyword-info-container .tabs {
  border-color: #ffffff0d;
}

body[data-dt='1'] .keyword-info-container .tabs li {
  color: #fff;
}

body[data-dt='1'] .keyword-info-container .tabs li.active {
  color: #4285f4;
}

body[data-dt='1'] table.keyword-info-table {
  color: #fff;
}

body[data-dt='1'] .keyword-info-table th {
  color: #fff;
}

body[data-dt='1'] .keyword-info-table tfoot tr {
  background-color: #3d3d3d;
}

body[data-dt='1'] .keyword-info-table tfoot tr:last-child td .button-arrow {
  border-color: #fff;
}
</style><style type="text/css">.tippy-touch{cursor:pointer!important}.tippy-notransition{transition:none!important}.tippy-popper{max-width:400px;-webkit-perspective:800px;perspective:800px;z-index:9999;outline:0;transition-timing-function:cubic-bezier(.165,.84,.44,1);pointer-events:none}.tippy-popper.html-template{max-width:96%;max-width:calc(100% - 20px)}.tippy-popper[x-placement^=top] [x-arrow]{border-top:7px solid #333;border-right:7px solid transparent;border-left:7px solid transparent;bottom:-7px;margin:0 9px}.tippy-popper[x-placement^=top] [x-arrow].arrow-small{border-top:5px solid #333;border-right:5px solid transparent;border-left:5px solid transparent;bottom:-5px}.tippy-popper[x-placement^=top] [x-arrow].arrow-big{border-top:10px solid #333;border-right:10px solid transparent;border-left:10px solid transparent;bottom:-10px}.tippy-popper[x-placement^=top] [x-circle]{-webkit-transform-origin:0 33%;transform-origin:0 33%}.tippy-popper[x-placement^=top] [x-circle].enter{-webkit-transform:scale(1) translate(-50%,-55%);transform:scale(1) translate(-50%,-55%);opacity:1}.tippy-popper[x-placement^=top] [x-circle].leave{-webkit-transform:scale(.15) translate(-50%,-50%);transform:scale(.15) translate(-50%,-50%);opacity:0}.tippy-popper[x-placement^=top] .tippy-tooltip.light-theme [x-circle]{background-color:#fff}.tippy-popper[x-placement^=top] .tippy-tooltip.light-theme [x-arrow]{border-top:7px solid #fff;border-right:7px solid transparent;border-left:7px solid transparent}.tippy-popper[x-placement^=top] .tippy-tooltip.light-theme [x-arrow].arrow-small{border-top:5px solid #fff;border-right:5px solid transparent;border-left:5px solid transparent}.tippy-popper[x-placement^=top] .tippy-tooltip.light-theme [x-arrow].arrow-big{border-top:10px solid #fff;border-right:10px solid transparent;border-left:10px solid transparent}.tippy-popper[x-placement^=top] .tippy-tooltip.transparent-theme [x-circle]{background-color:rgba(0,0,0,.7)}.tippy-popper[x-placement^=top] .tippy-tooltip.transparent-theme [x-arrow]{border-top:7px solid rgba(0,0,0,.7);border-right:7px solid transparent;border-left:7px solid transparent}.tippy-popper[x-placement^=top] .tippy-tooltip.transparent-theme [x-arrow].arrow-small{border-top:5px solid rgba(0,0,0,.7);border-right:5px solid transparent;border-left:5px solid transparent}.tippy-popper[x-placement^=top] .tippy-tooltip.transparent-theme [x-arrow].arrow-big{border-top:10px solid rgba(0,0,0,.7);border-right:10px solid transparent;border-left:10px solid transparent}.tippy-popper[x-placement^=top] [data-animation=perspective]{-webkit-transform-origin:bottom;transform-origin:bottom}.tippy-popper[x-placement^=top] [data-animation=perspective].enter{opacity:1;-webkit-transform:translateY(-10px) rotateX(0);transform:translateY(-10px) rotateX(0)}.tippy-popper[x-placement^=top] [data-animation=perspective].leave{opacity:0;-webkit-transform:translateY(0) rotateX(90deg);transform:translateY(0) rotateX(90deg)}.tippy-popper[x-placement^=top] [data-animation=fade].enter{opacity:1;-webkit-transform:translateY(-10px);transform:translateY(-10px)}.tippy-popper[x-placement^=top] [data-animation=fade].leave{opacity:0;-webkit-transform:translateY(-10px);transform:translateY(-10px)}.tippy-popper[x-placement^=top] [data-animation=shift].enter{opacity:1;-webkit-transform:translateY(-10px);transform:translateY(-10px)}.tippy-popper[x-placement^=top] [data-animation=shift].leave{opacity:0;-webkit-transform:translateY(0);transform:translateY(0)}.tippy-popper[x-placement^=top] [data-animation=scale].enter{opacity:1;-webkit-transform:translateY(-10px) scale(1);transform:translateY(-10px) scale(1)}.tippy-popper[x-placement^=top] [data-animation=scale].leave{opacity:0;-webkit-transform:translateY(0) scale(0);transform:translateY(0) scale(0)}.tippy-popper[x-placement^=bottom] [x-arrow]{border-bottom:7px solid #333;border-right:7px solid transparent;border-left:7px solid transparent;top:-7px;margin:0 9px}.tippy-popper[x-placement^=bottom] [x-arrow].arrow-small{border-bottom:5px solid #333;border-right:5px solid transparent;border-left:5px solid transparent;top:-5px}.tippy-popper[x-placement^=bottom] [x-arrow].arrow-big{border-bottom:10px solid #333;border-right:10px solid transparent;border-left:10px solid transparent;top:-10px}.tippy-popper[x-placement^=bottom] [x-circle]{-webkit-transform-origin:0 -50%;transform-origin:0 -50%}.tippy-popper[x-placement^=bottom] [x-circle].enter{-webkit-transform:scale(1) translate(-50%,-45%);transform:scale(1) translate(-50%,-45%);opacity:1}.tippy-popper[x-placement^=bottom] [x-circle].leave{-webkit-transform:scale(.15) translate(-50%,-5%);transform:scale(.15) translate(-50%,-5%);opacity:0}.tippy-popper[x-placement^=bottom] .tippy-tooltip.light-theme [x-circle]{background-color:#fff}.tippy-popper[x-placement^=bottom] .tippy-tooltip.light-theme [x-arrow]{border-bottom:7px solid #fff;border-right:7px solid transparent;border-left:7px solid transparent}.tippy-popper[x-placement^=bottom] .tippy-tooltip.light-theme [x-arrow].arrow-small{border-bottom:5px solid #fff;border-right:5px solid transparent;border-left:5px solid transparent}.tippy-popper[x-placement^=bottom] .tippy-tooltip.light-theme [x-arrow].arrow-big{border-bottom:10px solid #fff;border-right:10px solid transparent;border-left:10px solid transparent}.tippy-popper[x-placement^=bottom] .tippy-tooltip.transparent-theme [x-circle]{background-color:rgba(0,0,0,.7)}.tippy-popper[x-placement^=bottom] .tippy-tooltip.transparent-theme [x-arrow]{border-bottom:7px solid rgba(0,0,0,.7);border-right:7px solid transparent;border-left:7px solid transparent}.tippy-popper[x-placement^=bottom] .tippy-tooltip.transparent-theme [x-arrow].arrow-small{border-bottom:5px solid rgba(0,0,0,.7);border-right:5px solid transparent;border-left:5px solid transparent}.tippy-popper[x-placement^=bottom] .tippy-tooltip.transparent-theme [x-arrow].arrow-big{border-bottom:10px solid rgba(0,0,0,.7);border-right:10px solid transparent;border-left:10px solid transparent}.tippy-popper[x-placement^=bottom] [data-animation=perspective]{-webkit-transform-origin:top;transform-origin:top}.tippy-popper[x-placement^=bottom] [data-animation=perspective].enter{opacity:1;-webkit-transform:translateY(10px) rotateX(0);transform:translateY(10px) rotateX(0)}.tippy-popper[x-placement^=bottom] [data-animation=perspective].leave{opacity:0;-webkit-transform:translateY(0) rotateX(-90deg);transform:translateY(0) rotateX(-90deg)}.tippy-popper[x-placement^=bottom] [data-animation=fade].enter{opacity:1;-webkit-transform:translateY(10px);transform:translateY(10px)}.tippy-popper[x-placement^=bottom] [data-animation=fade].leave{opacity:0;-webkit-transform:translateY(10px);transform:translateY(10px)}.tippy-popper[x-placement^=bottom] [data-animation=shift].enter{opacity:1;-webkit-transform:translateY(10px);transform:translateY(10px)}.tippy-popper[x-placement^=bottom] [data-animation=shift].leave{opacity:0;-webkit-transform:translateY(0);transform:translateY(0)}.tippy-popper[x-placement^=bottom] [data-animation=scale].enter{opacity:1;-webkit-transform:translateY(10px) scale(1);transform:translateY(10px) scale(1)}.tippy-popper[x-placement^=bottom] [data-animation=scale].leave{opacity:0;-webkit-transform:translateY(0) scale(0);transform:translateY(0) scale(0)}.tippy-popper[x-placement^=left] [x-arrow]{border-left:7px solid #333;border-top:7px solid transparent;border-bottom:7px solid transparent;right:-7px;margin:6px 0}.tippy-popper[x-placement^=left] [x-arrow].arrow-small{border-left:5px solid #333;border-top:5px solid transparent;border-bottom:5px solid transparent;right:-5px}.tippy-popper[x-placement^=left] [x-arrow].arrow-big{border-left:10px solid #333;border-top:10px solid transparent;border-bottom:10px solid transparent;right:-10px}.tippy-popper[x-placement^=left] [x-circle]{-webkit-transform-origin:50% 0;transform-origin:50% 0}.tippy-popper[x-placement^=left] [x-circle].enter{-webkit-transform:scale(1) translate(-50%,-50%);transform:scale(1) translate(-50%,-50%);opacity:1}.tippy-popper[x-placement^=left] [x-circle].leave{-webkit-transform:scale(.15) translate(-50%,-50%);transform:scale(.15) translate(-50%,-50%);opacity:0}.tippy-popper[x-placement^=left] .tippy-tooltip.light-theme [x-circle]{background-color:#fff}.tippy-popper[x-placement^=left] .tippy-tooltip.light-theme [x-arrow]{border-left:7px solid #fff;border-top:7px solid transparent;border-bottom:7px solid transparent}.tippy-popper[x-placement^=left] .tippy-tooltip.light-theme [x-arrow].arrow-small{border-left:5px solid #fff;border-top:5px solid transparent;border-bottom:5px solid transparent}.tippy-popper[x-placement^=left] .tippy-tooltip.light-theme [x-arrow].arrow-big{border-left:10px solid #fff;border-top:10px solid transparent;border-bottom:10px solid transparent}.tippy-popper[x-placement^=left] .tippy-tooltip.transparent-theme [x-circle]{background-color:rgba(0,0,0,.7)}.tippy-popper[x-placement^=left] .tippy-tooltip.transparent-theme [x-arrow]{border-left:7px solid rgba(0,0,0,.7);border-top:7px solid transparent;border-bottom:7px solid transparent}.tippy-popper[x-placement^=left] .tippy-tooltip.transparent-theme [x-arrow].arrow-small{border-left:5px solid rgba(0,0,0,.7);border-top:5px solid transparent;border-bottom:5px solid transparent}.tippy-popper[x-placement^=left] .tippy-tooltip.transparent-theme [x-arrow].arrow-big{border-left:10px solid rgba(0,0,0,.7);border-top:10px solid transparent;border-bottom:10px solid transparent}.tippy-popper[x-placement^=left] [data-animation=perspective]{-webkit-transform-origin:right;transform-origin:right}.tippy-popper[x-placement^=left] [data-animation=perspective].enter{opacity:1;-webkit-transform:translateX(-10px) rotateY(0);transform:translateX(-10px) rotateY(0)}.tippy-popper[x-placement^=left] [data-animation=perspective].leave{opacity:0;-webkit-transform:translateX(0) rotateY(-90deg);transform:translateX(0) rotateY(-90deg)}.tippy-popper[x-placement^=left] [data-animation=fade].enter{opacity:1;-webkit-transform:translateX(-10px);transform:translateX(-10px)}.tippy-popper[x-placement^=left] [data-animation=fade].leave{opacity:0;-webkit-transform:translateX(-10px);transform:translateX(-10px)}.tippy-popper[x-placement^=left] [data-animation=shift].enter{opacity:1;-webkit-transform:translateX(-10px);transform:translateX(-10px)}.tippy-popper[x-placement^=left] [data-animation=shift].leave{opacity:0;-webkit-transform:translateX(0);transform:translateX(0)}.tippy-popper[x-placement^=left] [data-animation=scale].enter{opacity:1;-webkit-transform:translateX(-10px) scale(1);transform:translateX(-10px) scale(1)}.tippy-popper[x-placement^=left] [data-animation=scale].leave{opacity:0;-webkit-transform:translateX(0) scale(0);transform:translateX(0) scale(0)}.tippy-popper[x-placement^=right] [x-arrow]{border-right:7px solid #333;border-top:7px solid transparent;border-bottom:7px solid transparent;left:-7px;margin:6px 0}.tippy-popper[x-placement^=right] [x-arrow].arrow-small{border-right:5px solid #333;border-top:5px solid transparent;border-bottom:5px solid transparent;left:-5px}.tippy-popper[x-placement^=right] [x-arrow].arrow-big{border-right:10px solid #333;border-top:10px solid transparent;border-bottom:10px solid transparent;left:-10px}.tippy-popper[x-placement^=right] [x-circle]{-webkit-transform-origin:-50% 0;transform-origin:-50% 0}.tippy-popper[x-placement^=right] [x-circle].enter{-webkit-transform:scale(1) translate(-50%,-50%);transform:scale(1) translate(-50%,-50%);opacity:1}.tippy-popper[x-placement^=right] [x-circle].leave{-webkit-transform:scale(.15) translate(-50%,-50%);transform:scale(.15) translate(-50%,-50%);opacity:0}.tippy-popper[x-placement^=right] .tippy-tooltip.light-theme [x-circle]{background-color:#fff}.tippy-popper[x-placement^=right] .tippy-tooltip.light-theme [x-arrow]{border-right:7px solid #fff;border-top:7px solid transparent;border-bottom:7px solid transparent}.tippy-popper[x-placement^=right] .tippy-tooltip.light-theme [x-arrow].arrow-small{border-right:5px solid #fff;border-top:5px solid transparent;border-bottom:5px solid transparent}.tippy-popper[x-placement^=right] .tippy-tooltip.light-theme [x-arrow].arrow-big{border-right:10px solid #fff;border-top:10px solid transparent;border-bottom:10px solid transparent}.tippy-popper[x-placement^=right] .tippy-tooltip.transparent-theme [x-circle]{background-color:rgba(0,0,0,.7)}.tippy-popper[x-placement^=right] .tippy-tooltip.transparent-theme [x-arrow]{border-right:7px solid rgba(0,0,0,.7);border-top:7px solid transparent;border-bottom:7px solid transparent}.tippy-popper[x-placement^=right] .tippy-tooltip.transparent-theme [x-arrow].arrow-small{border-right:5px solid rgba(0,0,0,.7);border-top:5px solid transparent;border-bottom:5px solid transparent}.tippy-popper[x-placement^=right] .tippy-tooltip.transparent-theme [x-arrow].arrow-big{border-right:10px solid rgba(0,0,0,.7);border-top:10px solid transparent;border-bottom:10px solid transparent}.tippy-popper[x-placement^=right] [data-animation=perspective]{-webkit-transform-origin:left;transform-origin:left}.tippy-popper[x-placement^=right] [data-animation=perspective].enter{opacity:1;-webkit-transform:translateX(10px) rotateY(0);transform:translateX(10px) rotateY(0)}.tippy-popper[x-placement^=right] [data-animation=perspective].leave{opacity:0;-webkit-transform:translateX(0) rotateY(90deg);transform:translateX(0) rotateY(90deg)}.tippy-popper[x-placement^=right] [data-animation=fade].enter{opacity:1;-webkit-transform:translateX(10px);transform:translateX(10px)}.tippy-popper[x-placement^=right] [data-animation=fade].leave{opacity:0;-webkit-transform:translateX(10px);transform:translateX(10px)}.tippy-popper[x-placement^=right] [data-animation=shift].enter{opacity:1;-webkit-transform:translateX(10px);transform:translateX(10px)}.tippy-popper[x-placement^=right] [data-animation=shift].leave{opacity:0;-webkit-transform:translateX(0);transform:translateX(0)}.tippy-popper[x-placement^=right] [data-animation=scale].enter{opacity:1;-webkit-transform:translateX(10px) scale(1);transform:translateX(10px) scale(1)}.tippy-popper[x-placement^=right] [data-animation=scale].leave{opacity:0;-webkit-transform:translateX(0) scale(0);transform:translateX(0) scale(0)}.tippy-popper .tippy-tooltip.transparent-theme{background-color:rgba(0,0,0,.7)}.tippy-popper .tippy-tooltip.transparent-theme[data-animatefill]{background-color:transparent}.tippy-popper .tippy-tooltip.light-theme{color:#26323d;box-shadow:0 4px 20px 4px rgba(0,20,60,.1),0 4px 80px -8px rgba(0,20,60,.2);background-color:#fff}.tippy-popper .tippy-tooltip.light-theme[data-animatefill]{background-color:transparent}.tippy-tooltip{position:relative;color:#fff;border-radius:4px;font-size:.95rem;padding:.4rem .8rem;text-align:center;will-change:transform;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;background-color:#333}.tippy-tooltip--small{padding:.25rem .5rem;font-size:.8rem}.tippy-tooltip--big{padding:.6rem 1.2rem;font-size:1.2rem}.tippy-tooltip[data-animatefill]{overflow:hidden;background-color:transparent}.tippy-tooltip[data-interactive]{pointer-events:auto}.tippy-tooltip[data-inertia]{transition-timing-function:cubic-bezier(.53,2,.36,.85)}.tippy-tooltip [x-arrow]{position:absolute;width:0;height:0}.tippy-tooltip [x-circle]{position:absolute;will-change:transform;background-color:#333;border-radius:50%;width:130%;width:calc(110% + 2rem);left:50%;top:50%;z-index:-1;overflow:hidden;transition:all ease}.tippy-tooltip [x-circle]:before{content:"";padding-top:90%;float:left}@media (max-width:450px){.tippy-popper{max-width:96%;max-width:calc(100% - 20px)}}
</style><style type="text/css">.ubersuggest-header-container {
  box-sizing: border-box;
  width: 100%;
  font-size: 12px;
}

.ubersuggest-header-container .row {
  margin: 0;
  padding: 15px 16px 15px 16px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  min-height: 30px;
}

.ue-enable {
  display: block;
}

.ue-disable {
  display: none !important;
}

.ubersuggest-header-container .settings {
  display: flex;
  align-items: center;
  margin-right: 18px;
}
.ubersuggest-header-container .settings-label {
  margin-right: 21px;
}

.ubersuggest-header-container .settings-icon {
  width: 21px;
  height: 21px;
  margin-right: 7px;
}
</style><style type="text/css">.keyword-info-section {
  color: #26282d;
  font-family: Arial;
  font-size: 12px;
  padding: 8px 0 10px 8px;
  display: flex;
  align-items: center;
}

.keyword-info-section.hidden {
  display: none;
}

.keyword-info-section.google {
  background-color: #fff;
}

body[data-dt='1'] .keyword-info-section.google {
  background-color: #303134;
}

.keyword-info-section.youtube {
  margin-right: 15px;
  padding: 0 0 0 10px;
  height: 100%;
  background-color: #fff;
}

html[dark='true'] .keyword-info-section.youtube {
  background-color: #121212;
}

.keyword-info-section.amazon {
  padding: 13px 0;
  background-color: #fff;
}
</style><style type="text/css">.kw-overview-container {
  box-sizing: border-box;
  width: 673px;
  padding: 0;
  margin: 0;
  margin-top: 14px;
  font-size: 12px;
  font-family: Arial;
}
.kw-overview-container.youtube {
  box-sizing: border-box;
  width: 100%;
  padding: 0;
  margin: 0;
  font-size: 12px;
}
</style><style type="text/css">.bl-info-container {
  box-sizing: border-box;
  width: 100%;
  padding: 0;
  margin: 0;
  font-size: 12px;
}
.bl-info-header {
  display: flex;
  height: 24px;
  width: 100%;
  padding: 0;
  justify-content: space-between;
  cursor: pointer;
  box-sizing: border-box;
  margin-bottom: 5px;
}

.bl-info-header .row {
  display: flex;
  margin: 0;
  width: 100%;
  justify-content: space-between;
}

.bl-info-header .row.youtube {
  justify-content: flex-start;
}

.bl-info-content,
.kw-info-content {
  width: 100%;
  display: flex;
  flex-direction: column;
  border: 1px solid #dee1e5;
  border-radius: 8px;
  padding-top: 0;
}

body[data-dt='1'] .kw-info-content,
body[data-dt='1'] .bl-info-content {
  background: #2a2a2a;
  border-color: #2a2a2a;
}

.bl-info-content img.loading {
  width: 50px;
  margin: 0 auto;
  margin-bottom: 10px;
}

.kw-info-content img.loading {
  width: 50px;
  margin: 0 auto;
  margin-top: 10px;
  margin-bottom: 10px;
}

table.bl-info-table,
table.kw-info-table {
  border-collapse: collapse;
  width: 100%;
  color: #808185;
  font-size: 12px;
}

body[data-dt='1'] table.bl-info-table,
body[data-dt='1'] table.kw-info-table {
  color: #fff;
}

.bl-info-table thead,
.kw-info-table thead {
  height: 50px;
}

.bl-info-table tr,
.kw-info-table tr {
  width: 100%;
  max-width: 600px;
}

.bl-info-table th,
.kw-info-table th {
  padding: 10px;
  padding-left: 0;
  font-weight: bold;
  color: #000000;
  font-size: 11px;
  border-bottom: 1px solid #dee1e5;
}

body[data-dt='1'] .bl-info-table th,
body[data-dt='1'] .kw-info-table th {
  color: #fff;
}

.bl-info-table th:first-child,
.kw-info-table th:first-child {
  padding-left: 16px;
}

.bl-info-table th:last-child,
.kw-info-table th:last-child {
  border-right: none;
  padding-right: 16px;
}

.bl-info-table td,
.kw-info-table td {
  border-bottom: 1px solid #dee1e5;
  padding: 10px;
  padding-left: 0;
  max-width: 0;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  color: #000;
}

body[data-dt='1'] .bl-info-table td,
body[data-dt='1'] .kw-info-table td {
  color: #fff;
}

.bl-info-table td:first-child,
.kw-info-table td:first-child {
  border-left: none;
  padding-left: 16px;
}

.bl-info-table td:last-child,
.kw-info-table td:last-child {
  border-right: none;
  padding-right: 16px;
}

.bl-info-table tfoot tr:last-child td,
.kw-info-table tfoot tr:last-child td {
  border-bottom: none;
}

.bl-info-container .row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 10px;
  margin-bottom: 20px;
}
</style><style type="text/css">.statistics-graph-container {
  box-sizing: border-box;
  width: 100%;
  margin-bottom: 5px;
  font-size: 12px;
  padding: 0px 16px;
}

.statistics-graph-container .row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin: 10px 0 20px 0;
}

.statistics-graph-container .row .title {
  color: #26282d;
  font-size: 17px;
  font-weight: bold;
}

.statistics-graph-container .tabs {
  list-style: none;
  display: flex;
  justify-content: flex-start;
  border-bottom: 1px solid #dee1e5;
  margin: 0 -16px;
  margin-bottom: 10px;
  margin-top: -10px;
  padding: 0px 16px;
}

.statistics-graph-container .tabs li {
  display: flex;
  align-items: center;
  padding: 8px 16px;
  color: #000;
  cursor: pointer;
  font-size: 13px;
  border-bottom: 3px solid transparent;
}

.statistics-graph-container .tabs li:first-child {
  margin-left: -16px;
}

.statistics-graph-container .tabs li.active {
  color: #4285f4;
  border-bottom: 3px solid #4285f4;
}

/* Google Dark Theme */
body[data-dt='1'] .statistics-graph-container .row .title {
  color: #fff;
}

body[data-dt='1'] .statistics-graph-container .tabs {
  border-bottom: 1px solid #dee1e5;
}

body[data-dt='1'] .statistics-graph-container .tabs li {
  color: #fff;
}

body[data-dt='1'] .statistics-graph-container .tabs li.active {
  color: #fff;
  border-bottom: 3px solid #fff;
}

.navbar-semi-dark .navbar-header {
    background: #ffffff !important;
}
.header-navbar .navbar-header .navbar-brand .brand-logo {
    width: 80% !important;
}
[data-menu=vertical-menu-modern] .navbar-dark .navbar-header .navbar-nav .nav-link, [data-menu=vertical-menu-modern] .navbar-semi-dark .navbar-header .navbar-nav .nav-link {
    color: #2c343b !important;
}
</style><style type="text/css">/* Chart.js */
@keyframes chartjs-render-animation{from{opacity:.99}to{opacity:1}}.chartjs-render-monitor{animation:chartjs-render-animation 1ms}.chartjs-size-monitor,.chartjs-size-monitor-expand,.chartjs-size-monitor-shrink{position:absolute;direction:ltr;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1}.chartjs-size-monitor-expand>div{position:absolute;width:1000000px;height:1000000px;left:0;top:0}.chartjs-size-monitor-shrink>div{position:absolute;width:200%;height:200%;left:0;top:0}</style>
    @yield('css_section')
  </head>
  <body class="vertical-layout vertical-menu-modern 2-columns fixed-navbar  menu-expanded pace-done" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns" data-new-gr-c-s-check-loaded="14.1056.0" data-gr-ext-installed=""><div class="pace  pace-inactive"><div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
  <div class="pace-progress-inner"></div>
</div>
<div class="pace-activity"></div></div>

    <!-- BEGIN: Header-->
    <nav class="header-navbar navbar-expand-lg navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-dark navbar-shadow">
      <div class="navbar-wrapper">
        <div class="navbar-header">
          <ul class="nav navbar-nav flex-row">
            <li class="nav-item mobile-menu d-lg-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs is-active" href="/affiliate"><i class="ft-menu font-large-1"></i></a></li>
            <li class="nav-item mr-auto"><a class="navbar-brand" href="/affiliate"><img class="brand-logo" alt="modern admin logo" src="https://livetutorials.in/ltlogo.png">
                </a></li>
            <li class="nav-item d-none d-lg-block nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="toggle-icon ft-toggle-right font-medium-3 white" data-ticon="ft-toggle-right"></i></a></li>
            <li class="nav-item d-lg-none"><a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="la la-ellipsis-v"></i></a></li>
          </ul>
        </div>
        <div class="navbar-container content">
          <div class="collapse navbar-collapse" id="navbar-mobile">
            
            <ul class="nav navbar-nav mr-auto float-left">
            </ul>
            <ul class="nav navbar-nav float-right">
            
            
             
              <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown"><span class="mr-1 user-name text-bold-700">{{Session::get("aff")->name}}</span><i class="ft-user"></i></a>
                <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="/affiliate/edit-profile"><i class="ft-user"></i> Edit Profile</a>
                  <div class="dropdown-divider"></div><a class="dropdown-item" href="/affiliate/logout"><i class="ft-power"></i> Logout</a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->

    <div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true" style="touch-action: none; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
      <div class="main-menu-content ps ps--active-y">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
        
         
         
          <li class=" nav-item"><a href="/affiliate" ><i class="la la-home"></i><span class="menu-title" data-i18n="Dashboard">Dashboard</span></a>
          </li>
      
           <li class=" nav-item"><a href="/affiliate/earnings" ><i class="la la-dollar"></i><span class="menu-title" data-i18n="Earnings">Earnings</span></a>
          </li>
          
            <li class=" nav-item"><a href="/affiliate/withdrawl" ><i class="la la-bank"></i><span class="menu-title" data-i18n="Withdrawl">Withdrawl</span></a>
          </li>

           <li class=" nav-item"><a href="/affiliate/bank" ><i class="la la-bank"></i><span class="menu-title" data-i18n="Withdrawl">Bank Details</span></a>
          </li>
        </ul>
      <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; height: 522px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 61px;"></div></div></div>
    </div>







        <!-- Page Sidebar Ends-->
       <div class="app-content content">
      <div class="content-overlay"></div>
      <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
        @yield('content')
      </div>
      </div>
      </div>
        
        <footer class="footer footer-static footer-light navbar-border navbar-shadow">
      <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2"><span class="float-md-left d-block d-md-inline-block">Copyright © 2022 <a class="text-bold-800 grey darken-2" href="https://livetutorials.in/" target="_blank">Livetutorials</a></span><span class="float-md-right d-none d-lg-block">Hand-crafted &amp; Made with<i class="ft-heart pink"></i><span id="scroll-top"></span></span></p>
    </footer>


    <!-- BEGIN: Vendor JS-->
    <script src="/aff/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="/aff/js/chart.min.js"></script>
    <script src="/aff/js/raphael-min.js"></script>
    <script src="/aff/js/morris.min.js"></script>
    <script src="/aff/js/jquery-jvectormap-2.0.3.min.js"></script>
    <script src="/aff/js/jquery-jvectormap-world-mill.js"></script>
  
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="/aff/js/app-menu.min.js"></script>
    <script src="/aff/js/app.min.js"></script>
    <script src="/aff/js/footer.min.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="/aff/js/dashboard-sales.min.js"></script>
    <!-- END: Page JS-->

  
  <!-- END: Body-->
<div class="jvectormap-tip"></div><div class="ue-sidebar-container"></div>


    @yield('js_section')
  </body>
</html>