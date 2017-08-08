module.exports = function(grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		stylelint: {
			src: [ "src/*.css" ]
		},

		concat: {
			options: {
				sourceMap: true
			},
			dist: {
				src: 'src/*.css',
				dest: 'tmp-style.css'
			}
		},

		postcss: {
			options: {
				map: true,
				diff: false,
				processors: [
					require( "autoprefixer" )( {
						browsers: [ "> 1%", "ie 8-11", "Firefox ESR" ]
					} )
				]
			},
			dist: {
				src: "tmp-style.css",
				dest: "style.css"
			}
		},

		clean: {
			options: {
				force: true
			},
			temp: [ 'tmp-style.css', 'tmp-style.css.map' ]
		},

		phpcs: {
			plugin: {
				src: './'
			},
			options: {
				bin: "vendor/bin/phpcs --extensions=php --ignore=\"*/vendor/*,*/node_modules/*\"",
				standard: "phpcs.ruleset.xml"
			}
		}
	});

	grunt.loadNpmTasks( "grunt-postcss" );
	grunt.loadNpmTasks( "grunt-contrib-concat" );
	grunt.loadNpmTasks( "grunt-contrib-clean" );
	grunt.loadNpmTasks( "grunt-phpcs" );
	grunt.loadNpmTasks( "grunt-stylelint" );

	// Default task(s).
	grunt.registerTask('default', [ 'stylelint', 'concat', 'postcss', 'clean', 'phpcs' ]);
};
