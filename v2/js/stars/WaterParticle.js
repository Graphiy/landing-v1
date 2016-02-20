var WaterParticle = function() {
	var wp = this;
  var colors = ["193,3,3", "91,188,71", "0,139,254", "255,206,11"]
  var colors = ["#c10303", "#5BBC47", "#008BFE", "#ffce0b"]
  // Tetrad complimentary colors
  //var colors = ["#ff4e4e", "#ffab4e", "#56ccff", "#4eff4e"]
	
	wp.x = 0;
	wp.y = 0;
	wp.z = Math.random() * 1 + 0.3;
	wp.size = Math.random()*5;
	//wp.opacity = Math.random() * 0.8 + 0.1;
  wp.color = colors[Math.floor(Math.random()*4)]
  
	
	wp.update = function(bounds) {
		if(wp.x == 0 || wp.y == 0) {
			wp.x = Math.random() * (bounds[1].x - bounds[0].x) + bounds[0].x;
			wp.y = Math.random() * (bounds[1].y - bounds[0].y) + bounds[0].y;
		}
		
		// Wrap around screen
		wp.x = wp.x < bounds[0].x ? bounds[1].x : wp.x;
		wp.y = wp.y < bounds[0].y ? bounds[1].y : wp.y;
		wp.x = wp.x > bounds[1].x ? bounds[0].x : wp.x;
		wp.y = wp.y > bounds[1].y ? bounds[0].y : wp.y;
	};
	
	wp.draw = function(context) {
    
    var gradient = context.createRadialGradient(wp.x,wp.y,0, wp.x,wp.y,wp.size);
    gradient.addColorStop(0, wp.color);
    gradient.addColorStop(0.5, wp.color);
    gradient.addColorStop(0.85, 'rgb(238,238,238)');
    gradient.addColorStop(1, 'rgb(238,238,238)');

    context.fillStyle = gradient;
    //context.fillRect(0,0,canvas.height,canvas.width);

    //Draw circle
		//context.fillStyle = 'rgb('+wp.color+')';
    //context.fillStyle = '#fff';
    context.beginPath();
    context.arc(wp.x, wp.y, this.z * this.size, 0, Math.PI*2, true);
    context.closePath();
    context.fill();
	};
}
