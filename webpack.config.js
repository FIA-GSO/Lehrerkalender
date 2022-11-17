import {resolve} from 'path';
import MiniCssExtractPlugin from "mini-css-extract-plugin";
import IgnoreEmitPlugin from "ignore-emit-webpack-plugin";
import CopyPlugin from 'copy-webpack-plugin';

let webpack = import('webpack');

const ruleCss = {
    test: /\.css$/,
    use: [
        MiniCssExtractPlugin.loader,
        {
            loader: 'css-loader',
            options:
                {
                    sourceMap: true,
                    importLoaders: 1,
                }
        },
    ],
};

const ruleScss = {
    test: /\.scss$/,
    use: [
        MiniCssExtractPlugin.loader,
        {
            loader: 'css-loader',
            options:
                {
                    sourceMap: true,
                    importLoaders: 2,
                }
        },
        {
            loader: 'sass-loader',
            options:
                {
                    sourceMap: true,
                    additionalData: "$env: " + process.env.NODE_ENV + ";"
                }
        },
    ],
};

const ruleFont = {
    test: /\.(ttf|otf|eot|woff(2)?)$/,
    type: 'asset/resource',
    generator: {
        filename: 'fonts/[name].[contenthash][ext][query]'
    }
};

const ruleJs = {
    test: /\.js$/,
    use: [
        {
            loader: 'babel-loader',
            options: {
                cacheDirectory: true
            }
        }
    ]
}

const ruleImagesSvg = {
    test: /\.svg$/,
    type: 'asset/resource',
    generator: {
        filename: 'images/svg/[name].[contenthash][ext][query]'
    },
    use: 'svgo-loader'
};

const ruleImagesBg = {
    test: /\/bg\//,
    type: 'asset/resource',
    generator: {
        filename: 'images/bg/[name].[contenthash][ext][query]'
    },
};

const ruleImagesIcons = {
    test: /\/icons\//,
    type: 'asset/resource',
    generator: {
        filename: 'images/icons/[name].[contenthash][ext][query]'
    },
};

let rules = [
    ruleCss,
    ruleScss,
    ruleJs,
    ruleFont,
    ruleImagesSvg,
    ruleImagesBg,
    ruleImagesIcons,
    {
        test: /\.woff($|\?)|\.woff2($|\?)|\.ttf($|\?)|\.eot($|\?)|\.svg($|\?)/i,
        type: 'asset/resource',
        generator: {
            filename: 'fonts/[name][ext][query]'
        }
    }
];

const pluginMiniCssExtract = new MiniCssExtractPlugin({
    filename: './css/[name].min.css',
    chunkFilename: './css/[name].min.css',
});

const pluginIgnoreEmit = new IgnoreEmitPlugin(/css\..*\.js$/igm);

const pluginCopy = new CopyPlugin(
    {
        patterns: [
            {
                from: './public/typo3conf/ext/chanathale_customer/Resources/Public/Images/logo',
                to: './public/assets/images/logo'
            }
        ]
    },
    {
        ignore: [
            '*.git*',
            '*.DS_Store*'
        ]
    }
);

let plugins = [
    pluginMiniCssExtract,
    pluginIgnoreEmit
];

let entryJs = {
    'js.main': './public/typo3conf/ext/chanathale_customer/Resources/Private/Assets/JavaScript/main.js',
};

// key must start with "css."
let entryCss = {
    'css.main': './packages/chanathale_customer/Resources/Private/Assets/Sass/main.scss'
};

export default {
    entry: {...entryJs, ...entryCss},
    output: {
        path: resolve('./public/assets'),
        filename: './js/[name].min.js',
        publicPath: '/assets/',
        chunkFilename: './js/chunks/[name].[contenthash].min.js'
    },
    plugins: plugins,
    module: {
        rules: rules
    },
    resolve: {
        extensions: ['*', '.js', '.scss', '.css', '.sass', '.jsx', '.ts']
    },
    stats: {
        warnings: false,
        children: true,
        errorDetails: true
    },
    performance: {
        hints: false
    },
    optimization: {
        minimize: true
    }
};