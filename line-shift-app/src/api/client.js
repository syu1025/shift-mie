import axios from 'axios';

const apiClient = axios.create({
  baseURL: process.env.REACT_APP_API_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

export const saveShifts = async (lineUserId, shifts) => {
  try {
    const response = await apiClient.post('/shifts', {
      line_user_id: lineUserId,
      shifts: shifts,
    });
    return response.data;
  } catch (error) {
    console.error('シフトの保存に失敗しました:', error);
    throw error;
  }
};

export const getShifts = async (lineUserId, startDate, endDate) => {
  try {
    const response = await apiClient.get('/shifts', {
      params: {
        line_user_id: lineUserId,
        start_date: startDate,
        end_date: endDate,
      },
    });
    return response.data;
  } catch (error) {
    console.error('シフトの取得に失敗しました:', error);
    throw error;
  }
}; 