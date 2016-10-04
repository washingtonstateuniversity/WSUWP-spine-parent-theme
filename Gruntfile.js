var Promise = require('es6-promise').polyfill();

module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

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

        csslint: {
            main: {
                src: [ "style.css" ],
                options: {
                    "adjoining-classes": false,
                    "box-sizing": false,                  // unless we want to support IE7
                    "compatible-vendor-prefixes": false,  // The library on this is older than autoprefixer.
                    "fallback-colors": false,             // unless we want to support IE8
                    "font-sizes": false,                  // audit
                    "gradients": false,                   // The library on this is older than autoprefixer.
                    "ids": false,
                    "important": false,                   // This should be set to 2 one day.
                    "order-alphabetical": false,
                    "overqualified-elements": false,      // We have weird uses that will always generate warnings.
                    "qualified-headings": false,
                    "known-properties": 1,                // Okay to ignore in the case of known unknowns.
                    "box-model": 2,
                    "display-property-grouping": 2,
                    "duplicate-background-images": 2,
                    "duplicate-properties": 2,
                    "empty-rules": 2,
                    "floats": 2,
                    "outline-none": 2,
                    "regex-selectors": 2,
                    "shorthand": 2,
                    "star-property-hack": 2,
                    "text-indent": 2,
                    "unique-headings": 2,
                    "universal-selector": 2,
                    "unqualified-attributes": 2,
                    "vendor-prefix": 2,
                    "zero-units": 2
                }
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
    grunt.loadNpmTasks( "grunt-contrib-csslint" );
    grunt.loadNpmTasks( "grunt-contrib-clean" );
    grunt.loadNpmTasks( "grunt-phpcs" );

    // Default task(s).
    grunt.registerTask('default', ['concat', 'postcss', 'csslint', 'clean' ]);
};
