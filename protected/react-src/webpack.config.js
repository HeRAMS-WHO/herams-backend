const path = require("path");
const HtmlWebpackPlugin = require("html-webpack-plugin");
const webpack = require('webpack');

module.exports = {
    entry: "./src/index.tsx",
    mode: "development",
    target: "web",
    output: {
        path: path.resolve(__dirname, "dist"),
        filename: "bundle.js",
        publicPath: '/static/js/',
    },
    devServer: {
        static: {
            directory: path.join(__dirname, 'dist'),
        },
        allowedHosts: ['react.herams.test'],
        compress: true,
        port: 9090, // Change the port to 9090
        hot: true,
        historyApiFallback: true,
    },
    plugins: [
        new HtmlWebpackPlugin({
            template: "./src/index.html",
        }),
        new webpack.HotModuleReplacementPlugin()
    ],
    resolve: {
        extensions: [".js", ".ts", ".tsx", ".jsx"],
    },
    module: {
        rules: [
            // TypeScript files
            {
                test: /\.(ts|tsx)$/,
                exclude: /node_modules/,
                use: {
                    loader: "babel-loader",
                    options: {
                        presets: [
                            "@babel/preset-env",
                            "@babel/preset-react",
                            "@babel/preset-typescript",
                        ],
                    },
                },
            },
            // JavaScript files
            {
                test: /\.(js|jsx)$/,
                exclude: /node_modules/,
                use: {
                    loader: "babel-loader",
                    options: {
                        presets: [
                            "@babel/preset-env",
                            "@babel/preset-react",
                        ],
                    },
                },
            },
            // CSS files
            {
                test: /\.css$/,
                exclude: /node_modules\/(?!survey-core|survey-creator-core|ag-grid-community).*/,
                use: ["style-loader", "css-loader"],
            },

            // Image assets
            {
                test: /\.(png|svg)$/i,
                type: "asset/resource",
            },
        ],
    },
};
