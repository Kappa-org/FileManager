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
						'<%= paths.dist %>/js/<%= pkg.name %>.js'
					]
				}
			}
		},
		cssmin: {
			build: {
				files: {
					'<%= paths.dist %>/css/<%= pkg.name %>.css': [
						'<%= paths.components %>/bootstrap/bootstrap.css',
						'<%= paths.dist %>/css/<%= pkg.name %>.css'
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
					'<%= paths.dist %>/js/<%= pkg.name %>.js': [
						'<%= paths.assets %>/coffee/*.coffee',
						'!<%= paths.assets %>/coffee/bootstrap.coffee',
						'<%= paths.assets %>/coffee/bootstrap.coffee'
					]
				}
			}
		},
		less: {
			build: {
				files: {
					'<%= paths.dist %>/css/<%= pkg.name %>.css': [
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