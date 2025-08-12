// .prettierrc.cjs
module.exports = {
    plugins: ["prettier-plugin-blade"],
    overrides: [
        {
            files: "*.blade.php",
            options: {
                parser: "blade",
                singleQuote: true,
                tabWidth: 4,
                printWidth: 120,
                wrapAttributes: "preserve",
            },
        },
    ],
};
