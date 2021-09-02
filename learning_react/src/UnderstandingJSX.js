import React from "react";

class UnderstandingJSX extends React.Component {
    render() {
        let author = {
            name : 'Sandro',
            middleName : 'Roberto',
            surName : 'Thomé'
        }

        return <div>
            <h2>Understanding JSX:</h2>
            <p>Trying to return a &#60;h2&#62; tag with a small paragraph. Lets see if it is going to work?!</p>
            <br/>
            <p>IT WORKED!</p>
            <br/>
            <a href="test_page.html">Click here to go to the new test page</a>
            <p>
                Each function can return only a single element, F.I.:<br/>
                If you want to return a div and a list<br/>
                within a function/component you have to put them together inside an external container,
                like another &#60;div&#62; or something like that.
            </p>
            <h3>Example:</h3>
            <br/>
            <div>Here is a &#60;div&#62;</div>
            <br/>
            <ol>
                <li>Here is a list,</li>
                <li>in which</li>
                <li>we have</li>
                <li>some items.</li>
            </ol>
            <p>In order to display a variable inside a text, you have to put it within brackets, like bellow:</p>
            <br/>
            <p>Text displaying a: &#123;variable_name&#125;.</p>
            <br/>
            <p>Created by: {testFunctionFormatUserName(author)}</p>
            <div>{addGoogleLogo()}</div>
        </div>;
    }
}

function testFunctionFormatUserName(userName) {
    return userName.name + ' ' + userName.middleName + ' ' + userName.surName;
}

function addGoogleLogo() {
    let googleLogo = 'https://google.com/google.jpg'
    return <img src={googleLogo} alt={googleLogo}/>
}

export default UnderstandingJSX;
