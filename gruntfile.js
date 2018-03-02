
module.exports = function (grunt) {
    grunt.loadNpmTasks('grunt-newer');
    grunt.loadNpmTasks('grunt-contrib-compass');
    grunt.loadNpmTasks('grunt-postcss');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-openport');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.initConfig({
        // Reference package.json
        pkg: grunt.file.readJSON('package.json'),

        // Compile SCSS with the Compass Compiler
        compass: {
            production: {
                options: {
                    sassDir: 'src/styles',
                    cssDir: 'assets/css/temp',
                    outputStyle: 'compressed',
                    cacheDir: 'src/styles/.sass-cache',
                    environment: 'production',
                    sourcemap: true
                },
            },
            development: {
                options: {
                    sassDir: 'src/styles',
                    cssDir: 'assets/css/temp',
                    cacheDir: 'src/styles/.sass-cache',
                    environment: 'development',
                    outputStyle: 'expanded',
                    sourcemap: true
                },
            },
        },
        postcss: {
            options: {
                map: true, // inline sourcemaps
                processors: [
                    require('pixrem')(), // add fallbacks for rem units
                    require('autoprefixer')({ browsers: 'last 3 version' }), // add vendor prefixes
                ]
            },
            public: {
                src: 'assets/css/temp/style.css',
                dest: 'style.css'
            },
            admin: {
                src: 'assets/css/temp/admin.css',
                dest: 'assets/css/admin.css'
            },
            clean: {
                src: 'assets/css/temp/themes/clean.css',
                dest: 'assets/css/clean.css'
            },
            modern: {
                src: 'assets/css/temp/themes/modern.css',
                dest: 'assets/css/modern.css'
            }
        },
        // Clean temp files
        clean: {
            temp_css: ['assets/css/temp/'],
        },
        // JSHint - Check Javascript for errors
        jshint: {
            options: {
                globals: {
                    jQuery: true,
                },
                smarttabs: true,
            },
            all: ['Gruntfile.js', 'src/scripts/**/*.js', '!assets/scripts/*.js', '!src/scripts/vendors/*.js'],
        },
        // Combine & minify JS
        uglify: {
            options: {
                sourceMap: true
            },
            public: {
                files: {
                    'assets/js/public.min.js': ['src/scripts/vendors/masonry.pkgd.min.js', 'src/scripts/include/jquery.wpgallery.js','src/scripts/include/jquery.elementqueries.js','src/scripts/include/jquery.toggleButtons.js','src/scripts/include/jquery.gspmenu.js', 'src/scripts/public.js']
                }
            },
            admin: {
                files: {
                    'assets/js/admin.min.js': ['src/scripts/admin.js']
                }
            }
        },

        // Watch
        watch: {
            options: {
                livereload: true,
            },
            cssPostProcess: {
                files: 'src/styles/**/*.scss',
                tasks: ['compass:development', 'newer:postcss', 'clean']
            },
            jsPostProcess: {
                files: ['src/scripts/**/*.js', '!scripts/dist/*.js'],
                tasks: ['newer:jshint', 'uglify'],
            },
            livereload: {
                files: ['styles/dist*.css', 'scripts/dist/*.js', '*.html', 'images/*', '*.php'],
            },
        },
    });
    grunt.registerTask( 'default', ['openport:watch.options.livereload:35731', 'watch'] );
};