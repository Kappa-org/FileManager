/**
 * This file is part of the Kappa/FileManager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

/**
 * This file is part of the Kappa/Sandbox package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

module.exports = function(grunt) {
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-coffee');
	grunt.loadNpmTasks('grunt-contrib-less');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-cssmin');

	grunt.registerTask('default', ['coffee', 'less', 'uglify', 'cssmin']);
	grunt.registerTask('js', ['coffee', 'uglify']);
	grunt.registerTask('css', ['less', 'cssmin']);

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		paths: {
			assets: 'client-side/assets',
			dist: 'client-side/dist',
			components: '<%= paths.assets %>/components'
		},
		uglify: {
			build: {
				files: {
					'<%= paths.dist %>/js/<%= pkg.name %>.js': [
						'<%= paths.components %>/jquery/jquery.js',
						'<%= paths.components %>/bootstrap/bootstrap.js',
						'<%= paths.components %>/nette/netteForms.js',
						'<%= paths.components %>/nette/nette.ajax.js',
						'<%= paths.assets %>/js/<%= pkg.name %>.js'
					],
					'<%= paths.dist %>/js/<%= pkg.name %>.forms.js': [
						'<%= paths.components %>/jquery/jquery.js',
						'<%= paths.components %>/nette/netteForms.js',
						'<%= paths.assets %>/js/<%= pkg.name %>.forms.js',
					]
				}
			}
		},
		cssmin: {
			build: {
				files: {
					'<%= paths.dist %>/css/<%= pkg.name %>.css': [
						'<%= paths.components %>/bootstrap/bootstrap.css',
						'<%= paths.assets %>/css/<%= pkg.name %>.css'
					]
				}
			}
		},
		coffee: {
			build: {
				options: {
					join: true
				},
				files: {
					'<%= paths.assets %>/js/<%= pkg.name %>.js': [
						'<%= paths.assets %>/coffee/bootstrap.coffee'
					],
					'<%= paths.assets %>/js/<%= pkg.name %>.forms.js': [
						'<%= paths.assets %>/coffee/Windows.coffee',
						'<%= paths.assets %>/coffee/forms.coffee'
					]
				}
			}
		},
		less: {
			build: {
				files: {
					'<%= paths.assets %>/css/<%= pkg.name %>.css': [
						'<%= paths.assets %>/less/*.less',
						'!<%= paths.assets %>/less/_*.less'
					]
				}
			}
		},
		watch: {
			coffee: {
				files: '<%= paths.assets %>/coffee/*.coffee',
				tasks: 'js'
			},
			less: {
				files: '<%= paths.assets %>/less/*.less',
				tasks: 'css'
			}
		}
	});
};