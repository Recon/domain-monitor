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
            glyphicons: {
                files: [
                    {
                        cwd: 'public_html/bower/bootstrap/fonts/',
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
                preserveComments: false,
            },
            dist_vendor: {
                files: {
                    'public_html/dist/js/vendor.min.js': ['public_html/dist/js/vendor.min.js'],
                    'public_html/dist/js/app.min.js': ['public_html/dist/js/app.min.js']
                }
            }
        },
        cssmin: {
            options: {
                keepSpecialComments: false,
                keepBreaks: true
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
        usebanner: {
            dist_licenses: {
                options: {
                    position: 'top',
                    banner: '// Complete list of open source libraries and their licenses is available in docs/external-packages.html \n' +
                    '// Generated: <%= grunt.template.today("dd-mm-yyyy") %>',
                    linebreak: true
                },
                files: {
                    src: [
                        'public_html/dist/css/style.min.css',
                        'public_html/dist/js/vendor.min.js'
                    ]
                }
            }
        },
        processhtml: {
            options: {},
            dist: {
                options: {
                    strip: false
                },
                files: {
                    'src/Views/index.php': ['src/Views/index.php'],
                    'src/Views/common/header_light.php': ['src/Views/common/header_light.php'],
                    'src/Views/common/footer_light.php': ['src/Views/common/footer_light.php']
                }
            },
            demo: {
                files: {
                    'public_html/templates/login.html': ['public_html/templates/login.html'],
                    'src/Views/index.php': ['src/Views/index.php'],
                }
            },
            cleanup: {
                options: {
                    strip: true
                },
                files: {
                    'public_html/templates/login.html': ['public_html/templates/login.html'],
                    'src/Views/index.php': ['src/Views/index.php'],
                }
            }
        },
        prettify: {
            options: {
                "indent": 4,
                "indent_char": " ",
                "indent_scripts": "normal",
                "wrap_line_length": 0,
                "brace_style": "collapse",
                "preserve_newlines": true,
                "max_preserve_newlines": 1,
                "unformatted": [
                    "code",
                    "pre"
                ]
            },
            docs: {
                expand: true,
                cwd: 'docs/',
                ext: '.html',
                src: ['*.html'],
                dest: 'docs/'
            }
        }
    });
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-processhtml');
    grunt.loadNpmTasks('grunt-banner');
    grunt.loadNpmTasks('grunt-prettify');
    grunt.registerTask('default', ['clean', 'concat', 'uglify', 'cssmin', 'copy', 'usebanner', 'processhtml:dist', 'processhtml:cleanup', 'prettify']);
    grunt.registerTask('demo', ['clean', 'concat', 'uglify', 'cssmin', 'copy', 'usebanner', 'processhtml:dist', 'processhtml:demo', 'prettify']);
};
