export function useCurrency() {
    const formatMoney = (value: number) => {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
        }).format(value).replace('$', '$ ');
    };

    const maskMoney = (event: Event) => {
        const input = event.target as HTMLInputElement;
        let value = input.value.replace(/\D/g, ""); 
        const numberValue = Number(value) / 100;
        const formatted = formatMoney(numberValue);
        input.value = formatted;
        return formatted;
    };

    return { formatMoney, maskMoney };
}