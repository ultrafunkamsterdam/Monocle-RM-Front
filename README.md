# Monocle-RM-Front
A Rocketmap Monocle frontend implementation. This has to be used together with my Monocle Repo (only postgres support!)

# After this beautiful image, there is a short howto on installation

Screenshot:
![alt text][logo]


# Install: 
Clone the repo
Change api/raw_data.php -> first block of code (valid referers and cors) : replace yoursite.tld with your actual site.
Change api/config.php -> change your postgres db credetials

In case you use nginx, this is a working snippet to put in your server directive:

```
#
#  This is the location block managing requests for uri's /raw_data and /gym_data
#
		
location /api {  
     
    # this is also set in php, but it can't do harm to also set it in nginx
	  # you can set multiple valid referers.
	
    valid_referers yoursite.tld www.yoursite.tld ;
         
	  if ($invalid_referer) {
	    
		# you can choose to display a custom page to tell users to 
		# stop trying to scrape or just return 302 https://yoursite.tld to 
		# be redirected back to your main map. You can also direct them to https://pornhub.com/ :)

        return 302 https://yoursite.tld/getthefuckoff.html; 
		
    }
}

#
# This is your main location block (actually this is nginx default)
#

location / {

    try_files $uri $uri/ =404;
             
}

#
# Rewrites for raw_data and gym_data
# 

rewrite ^/raw_data(.*)$ /api/raw_data.php?$1 last;
rewrite ^/gym_data(.*)$ /api/gym_data.php?$1 last;

```







[logo]: https://raw.githubusercontent.com/ultrafunkamsterdam/Rocketmap-Monocle/master/screenshot.png "Screenshot front-end"
