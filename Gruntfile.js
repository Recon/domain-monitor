module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        clean: ["public_html/dist"],
        concat: {
            options: {
                separator: ';'
            },
            vendors: {
                files: {
                    'public_html/dist/js/vendor.min.js': [
                        "public_html/bower/jquery/dist/jquery.js",
                        "public_html/bower/bootstrap/dist/js/bootstrap.js",
                        "public_html/bower/angular/angular.js",
                        "public_html/bower/moment/moment.js",
                        "public_html/bower/angular-loading-bar/build/loading-bar.js",
                        "public_html/bower/angular-route/angular-route.js",
                        "public_html/bower/bootstrap-confirmation2/bootstrap-confirmation.js",
                        "public_html/bower/amitava82-angular-multiselect/dist/multiselect-tpls.js"
                    ]
                }
            },
            app: {
                files: {
                    'public_html/dist/js/app.min.js': [
                        "public_html/js/monitor-metadata.js",
                        "public_html/angular/app.js",
                        "public_html/angular/config/**/*.js",
                        "public_html/angular/controllers/**/*.js",
                        "public_html/angular/services/**/*.js",
                        "public_html/angular/filters/**/*.js"
                    ],
                }
            }
        },
        copy: {
            fontawesome: {
                files: [
                    {
                        cwd: 'public_html/bower/font-awesome/fonts/',
                        src: '**',
                        dest: 'public_html/dist/fonts/',
                        expand: true
                    },
                ],
            },
        },
        uglify: {
            options: {
                mangle: false,
                drop_console: true,
                preserveComments: 'some'
            },
            dist_js: {
                files: {
                    'public_html/dist/js/vendor.min.js': ['public_html/dist/js/vendor.min.js'],
                    'public_html/dist/js/app.min.js': ['public_html/dist/js/app.min.js']
                }
            }
        },
        cssmin: {
            options: {
                keepSpecialComments: true
            },
            'default': {
                files: {
                    'public_html/dist/css/style.min.css': [
                        "public_html/bower/bootstrap/dist/css/bootstrap.css",
                        "public_html/bower/angular-loading-bar/build/loading-bar.css",
                        "public_html/bower/font-awesome/css/font-awesome.css",
                        "public_html/bower/amitava82-angular-multiselect/dist/multiselect.css",
                        "public_html/css/app.css",
                    ]
                }
            }
        },
        processhtml: {
            options: {},
            dist: {
                files: {
                    'src/Views/index.php': ['src/Views/index.php'],
                    'src/Views/common/header_light.php': ['src/Views/common/header_light.php'],
                    'src/Views/common/footer_light.php': ['src/Views/common/footer_light.php']
                }
            }
        },
    });
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-processhtml');
    grunt.registerTask('default', ['clean', 'concat', 'uglify', 'cssmin', 'copy', 'processhtml']);
};
