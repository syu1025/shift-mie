import React, { useState, useEffect } from 'react';
import { Calendar, Check, Clock, X, History, Home } from 'lucide-react';
import { saveShifts, getShifts } from '../api/client';

const LINEMiniApp = ({ liff }) => {
  const [selectedDates, setSelectedDates] = useState([]);
  const [currentMonth, setCurrentMonth] = useState(new Date());
  const [showDatePicker, setShowDatePicker] = useState(false);
  const [shifts, setShifts] = useState({});
  const [shiftType, setShiftType] = useState({});
  const [confirmationMode, setConfirmationMode] = useState(false);
  const [lineUserId, setLineUserId] = useState(null);

  useEffect(() => {
    if (liff.isLoggedIn()) {
      const profile = liff.getProfile();
      setLineUserId(profile.userId);
    }
  }, [liff]);

  // ... 既存のコード ...

  // シフト登録を完了
  const submitShifts = async () => {
    try {
      const shiftData = selectedDates.map(dateStr => ({
        date: dateStr,
        type: shiftType[dateStr] || 'time',
        start_time: shifts[dateStr]?.startTime || null,
        end_time: shifts[dateStr]?.endTime || null,
        lectures: shifts[dateStr]?.lectures || null,
      }));

      await saveShifts(lineUserId, shiftData);
      alert('シフトが登録されました！');
      setSelectedDates([]);
      setShifts({});
      setShiftType({});
      setConfirmationMode(false);
    } catch (error) {
      alert('シフトの登録に失敗しました。');
      console.error(error);
    }
  };

  // ... 残りの既存のコード ...
};

export default LINEMiniApp; 