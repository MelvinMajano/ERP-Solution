<?php

namespace Infrastructure\DTOs;

readonly class PaginationQueryDTO
{
    /**
     * @param array<string, mixed> $filters
     */
    public function __construct(
        public int $page = 1,
        public int $pageSize = 10,
        public ?string $sortBy = 'id',
        public ?string $sortDir = 'asc',
        public array $filters = []
    ) {}

    public static function fromValidatedData(array $validatedData, string $defaultSortBy = 'id'): self
    {
        $page = (int) ($validatedData['page'] ?? 1);
        $pageSize = (int) ($validatedData['pageSize'] ?? 10);
        $sortBy = isset($validatedData['sortBy']) ? (string) $validatedData['sortBy'] : $defaultSortBy;
        $sortDir = isset($validatedData['sortDir']) ? (string) $sortDir = $validatedData['sortDir'] : 'asc';

        unset($validatedData['page'], $validatedData['pageSize'], $validatedData['sortBy'], $validatedData['sortDir']);

        $filters = array_filter($validatedData, static fn($val) => $val !== null && $val !== '');

        return new self(
            page: $page,
            pageSize: $pageSize,
            sortBy: $sortBy,
            sortDir: $sortDir,
            filters: $filters
        );
    }

    public function toArray(): array
    {
        return [
            'page'     => $this->page,
            'pageSize' => $this->pageSize,
            'sortBy'   => $this->sortBy,
            'sortDir'  => $this->sortDir,
            'filters'  => $this->filters,
        ];
    }
}