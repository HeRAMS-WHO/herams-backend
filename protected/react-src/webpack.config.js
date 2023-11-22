const path = require("path");
const HtmlWebpackPlugin = require("html-webpack-plugin");
const { CleanWebpackPlugin } = require("clean-webpack-plugin");
const webpack = require("webpack");

module.exports = {
    entry: "./src/index.js",
    mode: "development",
    target: "web",
    output: {
        path: path.resolve(__dirname, "dist"),
        filename: "bundle.js",
        publicPath: '/static/js/',
    },
    devServer: {
        static: {
            directory: path.join(__dirname, "dist"),
        },
        allowedHosts: ["react.herams.test"],
        compress: true,
        port: 9090,
        hot: false,
        client: false,
        historyApiFallback: true,
    },
    plugins: [
        new HtmlWebpackPlugin({
            template: "./src/index.html",
        }),
        new CleanWebpackPlugin(),
        new webpack.ProvidePlugin({
            React: 'react',
        }),
        // new webpack.HotModuleReplacementPlugin(),
    ],
    resolve: {
        extensions: [".js", ".ts", ".tsx", ".jsx"],
        alias: {
            'React': path.resolve('./node_modules/react')
        }
    },
    module: {
        rules: [
            {
                test: /\.(js|jsx|ts|tsx)$/,
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
            {
                test: /\.css$/,
                exclude: /node_modules\/(?!survey-core|survey-creator-core|ag-grid-community).*/,
                use: ["style-loader", "css-loader"],
            },
            {
                test: /\.(png|svg)$/i,
                type: "asset/resource",
            },
        ],
    },
};
