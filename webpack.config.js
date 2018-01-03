module.exports = {
	devtool: 'inline-source-map',
	entry : "./jsfiles/index.js",
	output : {
		path: __dirname + "/dist",
		filename : "bundle.js"
	},
	module : {
		loaders : [{
		test: /\.js$/,
		exclude: /node_modules/,
		use: "babel-loader",
		}]
	}
}
