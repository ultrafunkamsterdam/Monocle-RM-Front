// define default excluded pokemon
var excludedPokemoDefault = JSON.parse('[10, 13, 16, 19, 21, 29, 32, 41, 46, 48, 54, 60, 79, 96, 118, 120, 129, 161, 163, 165, 167, 170, 177, 183, 187, 194, 198,220, 223]'),
    // get user excluded pokemon
    excludedPokemonUser = JSON.parse(localStorage.getItem('remember_select_exclude') || 0),
    // merge and order these 2 exclusion lists
    excludedPokemonAll = excludedPokemoDefault.concat(excludedPokemonUser).filter(
        function(elem, index, self) {
            return index == self.indexOf(elem);
        })
    // set exclusion list
localStorage.setItem('remember_select_exclude', '[' + excludedPokemonAll + ']')

// remove google advertisement stuff from the map
var checkgmload = setInterval(function() {
    if (!!google && typeof google === 'object' && !!google.maps && typeof google.maps === 'object') {
        clearInterval(checkgmload);
        setTimeout(function() {
            var cc = document.querySelectorAll('.gm-style-cc');
            [].forEach.call(cc, function(data) {
                data.remove()
            })
        }, 3000)
    }
}, 750)

// configure push
Push.config({
    serviceWorker: 'sw.js',
    fallback: function(payload) {

        alertify
            .delay(3000)
            .maxLogItems(4)
            .closeLogOnClick(true)
            .log(payload.title + ' (' + payload.body + ')', function(ev) {
                ev.preventDefault();
                centerMap(payload.data.lat, payload.data.lng, 15);
            })
    }
});




// Open Welcome Modal
swal({
    html: '<p>bla bla</p>\
           <p>More bla</p>',
    title: 'Welcome',
    showConfirmButton: true,
    confirmButtonText: 'show the map',
    confirmButtonColor: '#33cc33',
    imageWidth: 260,
    imageHeight: 66,
    animation: true
})
