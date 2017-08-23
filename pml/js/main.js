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
    html: '<p>Special thanks to all people who have showed their appreciation last weekend and DIRECTLY supported the continuation and unstoppable-ness of PokemapLive by donating!</p>\
    <p>This is something that will not be forgotten. In the near future, there might be some exclusive functionality and privileges for you donators, which I cannot reveal any details of yet.</p>',
    imageUrl: '/pml/img/logo/logo_small.jpg',
    title: 'You (donators) are amazing!',
    showConfirmButton: true,
    confirmButtonText: 'show the map',
    confirmButtonColor: '#33cc33',
    cancelButtonText: "why donate?",
    cancelButtonColor: '#ffb443',
    showCancelButton: true,
    imageWidth: 260,
    imageHeight: 66,
    animation: true
}).then(function() {
    console.log('closed')
}, function(dismiss) {
    swal({
        html: '<p>Niantic is making it harder and harder to maintain the map. \
        Started as a small fun project, it became a time- and money killing project.\
        to give you an idea:<br><br>\
        1000 accounts have to be created each 4 days (1000 captchas to solve, tutorials and hit level 5), increased hashing costs, \
        increased server costs due to higher traffic, purchasing of new ip addresses when there gets one banned again. \
        Furthermore, lots of time is spend to answer all social media requests and requests for support. And when you think everything is running fine finally, Niantic forces a new update and the panic starts to hit.\
        Alongside having a full time job, this is a lot of work (in case you wonder: i only did 1 raid yet, ever).<br><br> \
        Since Pokemaplive.NL is online, I have paid it all gladly for you all, without asking anything in return. This is your chance to show the love by donating some back\
        to keep this all running.<br> Donations can be made using the button below and is set to € 5. </p>',
        imageUrl: '/pml/img/logo/logo_small.jpg',
        showConfirmButton: true,
        confirmButtonText: 'Give support',
        confirmButtonColor: '#33cc33',
        cancelButtonText: "Don\'t give support",
        cancelButtonColor: '#ffb443',
        showCancelButton: true,
        imageWidth: 260,
        imageHeight: 66,
        animation: true,
        timer: 9999999
    }).then(function() {
        window.open("https://tikkie.me/pay/s1c87gh71e7s5f9ra50v", "new")
    }, function(dismiss) {
        console.log('closed support option')
    })

})
