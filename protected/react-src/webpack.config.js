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
        new webpack.ProvidePlugin({ //It contains the list of global components
            React: 'react',
            useNavigate: [path.resolve(__dirname, './src/components/common/router'), 'useNavigate'],
            Router: [path.resolve(__dirname, './src/components/common/router'), 'Router'],
            LinkButton: [path.resolve(__dirname, './src/components/common/router'), 'LinkButton'],
            Link: [path.resolve(__dirname, './src/components/common/router'), 'Link'],
        }),
        new webpack.ProvidePlugin({ //IT contains the list of global states
            info: [path.resolve(__dirname, './src/states/info'), 'default'],
            languageSelected: [path.resolve(__dirname, './src/states/languageSelected'), 'default'],
            autheticatedUser: [path.resolve(__dirname, './src/states/authenticatedUser'), 'default'],
            location: [path.resolve(__dirname, './src/states/location'), 'default'],
            params: [path.resolve(__dirname, './src/states/params'), 'default'],
            routeInfo: [path.resolve(__dirname, './src/states/routeInfo'), 'default'],
            specialVariables: [path.resolve(__dirname, './src/states/info'), 'specialVariables'],
            locales: [path.resolve(__dirname, './src/states/locales'), 'default'],
        }),
        new webpack.ProvidePlugin({ //It contains the list of global hooks and utils functions
            reloadInfo: [path.resolve(__dirname, './src/utils/reloadInfo'), 'default'],
            __: [path.resolve(__dirname, './src/utils/translationsUtility'), '__'],
            goToParent: [path.resolve(__dirname, './src/utils/goToParent'), 'default'],
            useReloadInfo: [path.resolve(__dirname, './src/hooks/useReloadInfo'), 'default'],
            reloadSpecialVariables: [path.resolve(__dirname, './src/states/info'), 'reloadSpecialVariables'],
            reloadInfo: [path.resolve(__dirname, './src/utils/reloadInfo'), 'default'],
            useReloadSpecialVariables: [path.resolve(__dirname, './src/hooks/useReloadSpecialVariables'), 'default'],
            replaceVariablesAsText: [path.resolve(__dirname, './src/utils/replaceVariablesAsText'), 'default'],
        })
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
