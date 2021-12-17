window.addEventListener('load', function() {

  setTimeout(function() {
    /* sidenav */
    let innerWidth = null;
    const sidenav  = document.getElementById('sidenav-1');
    const instance = mdb.Sidenav.getInstance(sidenav);
    setMode();
    window.addEventListener('resize', setMode);
    /* sidenav */
  }, 500);


});

const setMode = (e) => {
  // Check necessary for Android devices
  if (window.innerWidth === innerWidth) return;
  innerWidth = window.innerWidth;

  if (window.innerWidth < 1400) {
    instance.changeMode('over');
    instance.hide();
  } else {
    instance.changeMode('side');
    instance.show();
  }
};
