module.exports = {
	devtool: 'inline-source-map',
	entry : "./jsfiles/index.js",
	output : {
		path: __dirname + "/dist",
		filename : "bundle.js"
	},
	module : {
		loaders : [
		{
		test: /\.js$/,
		exclude: /node_modules/,
		use: "babel-loader",
		},
		{
			test: /\.scss$/,
			use: [
			"style-loader",
			"css-loader",
			"sass-loader"
			]
		},
		{
			test: /\.(png|jpg|jpeg)$/,
			use: [
			{
				loader: "url-loader",
				options: {limit:1000},
			}
			]
		}
		]
	}
}
