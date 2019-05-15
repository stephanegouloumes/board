import React from 'react'

function CardModal ({cardSelected, updateCard, removeCard, unselectCard}) {
        const card = cardSelected
        // const card = this.props.cardSelected ? this.props.cardLists[this.props.cardSelected.cardListIndex].cards[this.props.cardSelected.cardIndex] : null

        const modal = card ? (
            <div className="o-modal">
                <div className="o-modal__main c-card-modal">
                    <div className="c-card-modal__header">
                        <input type="text" value={card.title} onChange={(e) => updateCard('title', e.target.value)} />
                        <span className="o-modal__close" onClick={unselectCard}><i className="fas fa-times"></i></span>
                    </div>
                    <div className="c-card-modal__content">
                        <div className="c-card-modal__group">
                            <label>Description</label>
                            <textarea value={card.description ? card.description : ''} onChange={(e) => updateCard('description', e.target.value)}></textarea>
                        </div>
                        <button className="o-button o-button--danger o-button--small" onClick={removeCard}>Delete this card</button>
                    </div>
                </div>
            </div>
        ) : null

        return (
            <div>
                {modal}
                {modal && <div className="o-modal__overlay" onClick={unselectCard}></div>}
            </div>
        )
}

export default CardModal
