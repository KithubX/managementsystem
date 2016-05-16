module.exports = function(grunt){
	grunt.initConfig({
			pkg:grunt.file.readJSON('package.json'),
		watch:{
			sass:{
				files:['sass/*scss'],
				tasks:['sass']
			},
			js:{
				files:['js/*.js'],
				task:['uglify']
			}
		},
		sass:{
			options:{
				style:"compact"
			},
			dist:{
				files:{
					'css/main.css':"sass/main.scss"
				}
			}
		},
		uglify:{
			options:{
				manage:false
				//preserveComments:'none'
			},
			my_target:{
				options: {
		            report: 'min',
		            mangle: false
		        },
				files:{
					'js/main.min.js':['js/main.js']
				}
			}
		}
	});

	grunt.loadNpmTasks("grunt-contrib-watch");
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.registerTask("default",['watch']);
	grunt.registerTask("development",['uglify']);
}