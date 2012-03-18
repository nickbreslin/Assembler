$(function() {

    var cupcakes = [];
    cupcakes.push({ flavour : "#FFFFFF", rotation : 10  });
    cupcakes.push({ flavour : "#C35917", rotation : 60  });
    cupcakes.push({ flavour : "#D5145A", rotation : 200 });
    cupcakes.push({ flavour : "#13B7A7", rotation : 100 });


    var planets = [];
    planets.push({ flavour : "#D5145A" });
    planets.push({ flavour : "#13B7A7" });
    planets.push({ flavour : "#C35917" });
    planets.push({ flavour : "#FFFFFF" });


    Game = new function() {
	    
	    var supportRotate = false;


        /*
         * init
         */
    	this.init = function() {
            this.determineSupport();
            this.initCupcakes();
    	    this.initPlanets();

            // Tooltips
            $('.planet a').tooltip({
                'trigger'  : 'manual',
                'animation': false
            })
            .tooltip('show');
            
            // Starting Modal
            $('#myModal').modal({'keyboard':false});
        }


        /*
         * initCupcakes
         */
        this.initCupcakes = function() {
            // Float Cupcakes
            $.each($('.cupcake'), function(index, value) {
                Game.floatInSpace(value);
                Game.setFlavour(index);
            });
        
            // Make Cupcakes Draggable
            $( ".cupcake" ).draggable({
        	    start : function(event, ui) {
        	        $(this).stop();
                },
                stop : function(event, ui) {
        	        Game.floatInSpace(this);
                },
            });
        }


        /*
         * initPlanets
         */
        this.initPlanets = function() {
        	$( ".planet" ).droppable({
        		drop: function( event, ui ) {
        		    var cakeIdString = $(ui.draggable).attr('id');
        		    var cakeId = parseInt(cakeIdString.replace("cupcake-", ""));

        		    $(ui.draggable).remove();

        		    var planetIdString = $(this).attr('id');
           		    var planetId = parseInt(planetIdString.replace("planet-", ""));

                    if(planets[planetId].flavour == cupcakes[cakeId].flavour)
                    {
                        $('.face', this )
                            .removeClass('face-hungry face-angry')
           			        .addClass('face-happy');
           			   
           			    $('a', this )
            		        .tooltip('hide');
                    }
                    else
                    {
                        $('.face', this )
                            .removeClass('face-hungry face-happy')
                            .addClass('face-angry');
                    }
        		}
        	});
        }


        /*
         * determineSupport
         */
        this.determineSupport = function()
        {
            if ( navigator.userAgent.indexOf('Chrome') != -1
            || navigator.userAgent.indexOf('Firefox') != -1)
            {
                this.supportRotate = true;
            }
        }


        /*
         * setFlavour
         */
        this.setFlavour = function(index) {
            var want = "<span style='color:"+planets[index].flavour+"'>&#10084;</span>";
            $('.planet a').eq(index).attr('data-original-title', want); 
        }


        /*
         * floatInSpace
         */    
        this.floatInSpace = function(ele) {
            var elementStringId = $(ele).attr('id');
    		var elementId = parseInt(elementStringId.replace("cupcake-", ""));
        
            randTop  = this.getRand();
            randLeft = this.getRand();
            randTime = (this.getRand() * 10) + 5000;
            
            $(ele).animate({
                left: randTop,
                top: randLeft,
            },
            {
                step: function() {
                    if(Game.supportRotate)
                    {
                        Game.rotate(elementStringId, cupcakes[elementId].rotation);
                        cupcakes[elementId].rotation += 0.3;
                    }
                },
                complete : function() {
                    Game.floatInSpace(this);
                },
                duration: randTime
            });
        }


      /*
       * rotate
       */
      this.rotate = function(id, value)
      {
          document.getElementById(id).style.webkitTransform="rotate(" + value + "deg)";
          document.getElementById(id).style.msTransform="rotate(" + value + "deg)";
          document.getElementById(id).style.MozTransform="rotate(" + value + "deg)";
          document.getElementById(id).style.OTransform="rotate(" + value + "deg)";
          document.getElementById(id).style.transform="rotate(" + value + "deg)";      
      }


      /*
       * getRand
       */
      this.getRand = function()
      {
          var numLow = 0;
          var numHigh = 800;

          var adjustedHigh = (parseFloat(numHigh) - parseFloat(numLow)) + 1;

          var numRand = Math.floor(Math.random()*adjustedHigh) + parseFloat(numLow);
      
          return numRand;
      }
    };
    
    Game.init();
    

});