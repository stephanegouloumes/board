import React, { Component } from 'react'

export default class CardCreate extends Component {
    constructor(props) {
        super(props)

        this.state = {
            value: '',
            showButton: false
        }
    }

    componentWillMount() {
        document.addEventListener('mousedown', this.handleComponentClick, false)
    }

    componentWillUnmount() {
        document.removeEventListener('mousedown', this.handleComponentClick, false)
    }

    handleComponentClick = (e) => {
        if (this.node.contains(e.target)) {
            return;
        }

        this.setState({ showButton: false })
    }

    handleChange(e) {
        this.setState({ value: e.target.value })
    }

    handleInputClick(e) {
        this.setState({ showButton: true })
    }

    handleClick(e) {
        this.props.onAddCard(this.state.value)

        this.setState({ value: '', showButton: false })
    }

    render() {
        return (
            <div className="c-create-card" ref={node => this.node = node}>
                <input className="c-create-card__input" type="text" placeholder="Add a new card" value={this.state.value} onClick={() => this.handleInputClick()} onChange={(e) => this.handleChange(e)} />
                {this.state.showButton && this.state.value.length > 0 && <button className="c-create-card__button" onClick={(e) => this.handleClick(e)}>Add</button>}
            </div>
        )
    }
}
