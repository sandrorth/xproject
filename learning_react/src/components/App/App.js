import React, {useState} from 'react';
import styled from "styled-components";


// Components are located here
function App() {
    const [counter, increaseCounter] = useState(0);
    const btnClickIncrease = () => {
        increaseCounter(counter + 1);
    };
    const btnClickDecrease = () => {
        increaseCounter(counter - 1);
    };
    const btnClickReset = () => {
        increaseCounter(counter - counter);
    }

    return (
        <>
            <Div>
                <h2>You clicked {counter} times</h2>
                <Button onClick={btnClickIncrease}>Click here to increase the counter</Button>
                <Button onClick={btnClickDecrease}>Click here to decrease the counter</Button>
                <Button onClick={btnClickReset}>Click here to reset the counter</Button>
                <OrangeButton>Click here to do nothing</OrangeButton>
            </Div>
        </>
    );
}


const Div = styled.div`
    background-color: darkgrey;
    margin-top: 10px;
    text-align: center;
`;

const Button = styled.button`
    border-radius: 8%;
    margin-left: 4px;
    background-color: steelblue;
    width: 100px;
    height: 60px;
`;

const OrangeButton = styled(Button)`
    background-color: orange;
    border: 4px dashed darkslateblue;
`;

export default App;
