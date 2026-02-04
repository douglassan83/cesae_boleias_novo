<div class="accordion-item mb-2">
    <h2 class="accordion-header" id="heading-{{ $id }}">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapse-{{ $id }}" aria-expanded="false"
                aria-controls="collapse-{{ $id }}">
            {{ $pergunta }}
        </button>
    </h2>

    <div id="collapse-{{ $id }}" class="accordion-collapse collapse"
         aria-labelledby="heading-{{ $id }}" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
            {{ $resposta }}
        </div>
    </div>
</div>
